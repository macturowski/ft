<?php

namespace App\Http\Services\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;

use App\Exceptions\UserNotFoundException;

class UserDuelsHistoryService
{
    public function __construct(
        private User $user,
    ) {}

    public function getDuelsHistory(int $userId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);

        return response()->json(
            $user->duels->map->only([
                'id',
                'player_name',
                'opponent_name',
                'won'
            ])
        );
    }

    private function getUser(int $userId): ?User
    {
        return $this->user
            ->with([
                'duels' => function ($query) {
                    $query->whereStatus(1);
                }])
            ->whereId($userId)
            ->first();
    }
}
