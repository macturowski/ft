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
    public function __construct(
        private User $user,
        private Duel $duel,
        private DuelDetails $duelDetails,
        private LevelService $userLevelService,
        private CardService $cardService,
    ) {}

    public function storeUserDuelAction(int $userId, int $cardId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);
        
        $duel = $this->getDuel($userId);
        throw_if(is_null($duel), new UserDuelNotFoundException);

        $yourCard = $this->cardService->getCardById($cardId);

        throw_if(! in_array($yourCard['id'], $user->cards->pluck('card_id')->toArray()), new UserCardNotFoundException);
        throw_if(in_array($yourCard['id'], $duel->details->pluck('your_card_id')->toArray()), new UserCardUsedBeforeException);

        $opponentCardsIds = $duel->details->pluck('opponent_card_id')->toArray();
        $opponentavailableCards = array_column($this->cardService->getCardsExceptIds($opponentCardsIds), 'id');
        shuffle($opponentavailableCards);
        $opponentCard = $this->cardService->getCardById($opponentavailableCards[0]);

        $round = $duel->details->count() + 1;

        $this->store(
            $round,
            $yourCard['power'],
            $opponentCard['power'],
            $yourCard['id'],
            $opponentCard['id'],
            $duel->id,
        );
        
        if($round == 5) {
            $yourPoints = $duel->details->sum('your_points') + $yourCard['power'];
            $opponentPoints = $duel->details->sum('opponent_points') + $yourCard['power'];

            $this->closeDuel($duel, (int) $yourPoints > $opponentPoints);
        }

        return response()->json();
    }

    private function store(int $round, int $yourPoints, int $opponentPoints, int $yourCardId, int $opponentCardId, int $duelId): void
    {
        $this->duelDetails
            ->create([
                'round' => $round,
                'your_points' => $yourPoints,
                'opponent_points' => $opponentPoints,
                'your_card_id' => $yourCardId,
                'opponent_card_id' => $opponentCardId,
                'duel_id' => $duelId,
            ]);
    }

    private function closeDuel(Duel $duel, int $won): void
    {
        $duel->update([
            'status' => 1,
            'won' => 1,
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
            ->whereStatus(0)
            ->whereUserId($userId)
            ->first();
    }
}
