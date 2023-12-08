<?php

namespace App\Http\Services\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Services\Card\CardService;
use App\Exceptions\UserNotFoundException;
use App\Http\Services\Level\LevelService;

class UserDataService
{
    public function __construct(
        private User $user,
        private LevelService $userLevelService,
        private CardService $cardService,
    ) {}

    public function getUserData(int $userId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);

        return response()->json([
            'id' => $user->id,
            'username' => $user->name,
            'level' => $userLevel = $this->userLevelService->getLevel($user->getDuelsCount()),
            'level_points' => $this->userLevelService->getLevelPoints($user->getDuelsCount()),
            'cards' => $this->cardService->getCardsByIds($user->getCardsIds()),
            'new_card_allowed' => $this->cardService->isNewCardAllowed($userLevel, $user->getCardsCount()),
        ]);
    }

    private function getUser(int $userId): ?User
    {
        return $this->user
            ->with([
                'cards', 
                'duels' => function ($query) {
                    $query->wasWon();
                }
            ])
            ->whereId($userId)
            ->first();
    }
}
