<?php

namespace App\Http\Services\User;

use App\Models\Duel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Services\Card\CardService;
use App\Exceptions\UserNotFoundException;
use App\Http\Services\Level\LevelService;
use App\Exceptions\UserDuelNotFoundException;

class UserDuelActiveService
{
    private const ACTIVE_STATUS_FLAG = 'active';
    private const FINISHED_STATUS_FLAG = 'finished';

    public function __construct(
        private User $user,
        private Duel $duel,
        private CardService $cardService,
    ) {}

    public function getUserDuelActive(int $userId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);
        
        $duel = $this->getDuel($userId);
        throw_if(is_null($duel), new UserDuelNotFoundException);
        
        return response()->json([
            'round' => $duel->isClosed() ? $duel->getRounds() : $duel->getRounds() + 1,
            'your_points' => $duel->yourPoints(),
            'opponent_points' => $duel->opponentPoints(),
            'status' => $duel->isClosed() ? self::FINISHED_STATUS_FLAG : self::ACTIVE_STATUS_FLAG,
            'cards' => $this->cardService->getCardsByIds($user->getCardsIds()),
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
            ->whereUserId($userId)
            ->orderBy('id', 'desc')
            ->first();
    }
}
