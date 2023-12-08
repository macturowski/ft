<?php

namespace App\Http\Services\User;

use App\Models\Duel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Exceptions\UserHasActiveDuel;
use App\Exceptions\UserHasTooFewCards;
use App\Exceptions\UserNotFoundException;

class UserDuelStartService
{
    private const MIN_USER_CARDS_COUNT = 5;

    public function __construct(
        private User $user,
        private Duel $duel,
    ) {}

    public function storeNewDuel(int $userId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);
        throw_if($user->getCardsCount() < self::MIN_USER_CARDS_COUNT, new UserHasTooFewCards);
        throw_if($user->getDuelsCount() > 0, new UserHasActiveDuel);

        $this->store($userId, $user->name, fake()->name());

        return response()->json();
    }
    private function store(int $userId, string $playerName, string $opponentName): void
    {
        $this->duel
            ->create([
                'player_name' => $playerName,
                'opponent_name' => $opponentName,
                'user_id' => $userId,
            ]);
    }

    private function getUser(int $userId): ?User
    {
        return $this->user
            ->with([
                'cards',
                'duels' => function ($query) {
                    $query->whereStatus(Duel::STATUS_ACTIVE);
                }
            ])
            ->whereId($userId)
            ->first();
    }

}
