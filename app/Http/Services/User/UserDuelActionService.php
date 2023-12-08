<?php

namespace App\Http\Services\User;

use App\Models\Duel;
use App\Models\User;
use App\Models\DuelDetails;
use Illuminate\Http\JsonResponse;
use App\Http\Services\Card\CardService;
use App\Exceptions\UserNotFoundException;
use App\Http\Services\Level\LevelService;
use App\Exceptions\UserCardNotFoundException;
use App\Exceptions\UserDuelNotFoundException;
use App\Exceptions\UserCardUsedBeforeException;

class UserDuelActionService
{
    private const MAX_ROUNDS = 5;

    public function __construct(
        private User $user,
        private Duel $duel,
        private DuelDetails $duelDetails,
        private LevelService $userLevelService,
        private CardService $cardService,
    ) {}

    public function storeUserDuelAction(int $userId, ?int $cardId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);
        
        $duel = $this->getDuel($userId);
        throw_if(is_null($duel), new UserDuelNotFoundException);

        if(is_null($cardId)) {
            $yourNewCard['power'] = 0;
            $yourNewCard['id'] = null;
        } else {
            $yourNewCard = $this->cardService->getCardById($cardId);
            throw_if(! in_array($yourNewCard['id'], $user->getCardsIds()), new UserCardNotFoundException);
            throw_if(in_array($yourNewCard['id'],  $duel->getYourCardsIds()), new UserCardUsedBeforeException);
        }

        $opponentNewCardId = $this->cardService->getRandomCardIdFromBaseStackExceptIds($user->getCardsIds());
        $opponentNewCard = $this->cardService->getCardById($opponentNewCardId);
        $round = $duel->getRounds() + 1;

        $this->store(
            $round,
            $yourNewCard['power'],
            $opponentNewCard['power'],
            $yourNewCard['id'],
            $opponentNewCard['id'],
            $duel,
        );
        
        if($round == self::MAX_ROUNDS) {
            $yourPoints = $duel->yourPoints() + $yourNewCard['power'];
            $opponentPoints = $duel->opponentPoints() + $opponentNewCard['power'];
            $won = $yourPoints > $opponentPoints ? 1 : 0;

            $this->closeDuel($duel, $won);
        }

        return response()->json();
    }

    private function store(int $round, int $yourPoints, int $opponentPoints, ?int $yourCardId, int $opponentCardId, Duel $duel): void
    {
        $this->duelDetails
            ->create([
                'round' => $round,
                'your_points' => $yourPoints,
                'opponent_points' => $opponentPoints,
                'your_card_id' => $yourCardId,
                'opponent_card_id' => $opponentCardId,
                'duel_id' => $duel->id,
            ]);
    }

    private function closeDuel(Duel $duel, int $won): void
    {
        $duel->update([
            'status' => Duel::STATUS_FINISHED,
            'won' => $won,
        ]); 
    }

    private function getUser(int $userId): ?User
    {
        return $this->user
            ->with('cards')
            ->whereId($userId)
            ->first();
    }

    private function getDuel(int $userId): ?Duel
    {
        return $this->duel
            ->with('details')
            ->whereStatus(Duel::STATUS_ACTIVE)
            ->whereUserId($userId)
            ->first();
    }
}
