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

        if($duel->isClosed()) {
            return response()->json([
                'status' => self::FINISHED_STATUS_FLAG,
            ]);
        }
        
        return response()->json([
            'round' => $duel->details->count() + 1,
            'your_points' => $duel->details->sum('your_points'),
            'opponent_points' => $duel->details->sum('opponent_points'),
            'status' => self::ACTIVE_STATUS_FLAG,
            'cards' => $this->cardService->getCardsByIds($user->cards->pluck('card_id')->toArray()),
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
