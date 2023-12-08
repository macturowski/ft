<?php

namespace App\Http\Services\User;

use App\Models\User;
use App\Models\UserCard;
use Illuminate\Http\JsonResponse;
use App\Http\Services\Card\CardService;
use App\Exceptions\UserNotFoundException;
use App\Http\Services\Level\LevelService;
use App\Exceptions\UserCardLimitException;

class UserCardService
{
    public function __construct(
        private User $user,
        private UserCard $userCard,
        private LevelService $userLevelService,
        private CardService $cardService,
    ) {}

    public function storeNewCard(int $userId): JsonResponse
    {
        $user = $this->getUser($userId);
        throw_if(is_null($user), new UserNotFoundException);

        $userLevel = $this->userLevelService->getLevel($user->getDuelsCount());
        $userCardsCount = $user->getCardsCount();
        throw_if(! $this->cardService->isNewCardAllowed($userLevel, $userCardsCount), new UserCardLimitException);
        
        $newCardId = $this->cardService->getRandomCardIdFromBaseStackExceptIds($user->getCardsIds());
        $this->store($userId, $newCardId);

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    private function store(int $userId, int $cardId): void
    {
        $this->userCard
            ->create([
                'user_id' => $userId,
                'card_id' => $cardId,
            ]);
    }

    private function getUser(int $userId): ?User
    {
        return $this->user
            ->with([
                'cards', 
                'duels' => function ($query) {
                    $query->wasWon(1);
                }
            ])
            ->whereId($userId)
            ->first();
    }
}
