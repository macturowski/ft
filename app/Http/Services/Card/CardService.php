<?php

namespace App\Http\Services\Card;

use App\Http\Services\Level\LevelService;

class CardService
{
    private const CARDS_PER_LEVEL_1 = 5;
    private const CARDS_PER_LEVEL_2 = 10;
    private const CARDS_PER_LEVEL_3 = 15;
    private const CARDS_PER_LEVELS = [
        LevelService::LEVEL_1 => self::CARDS_PER_LEVEL_1,
        LevelService::LEVEL_2 => self::CARDS_PER_LEVEL_2,
        LevelService::LEVEL_3 => self::CARDS_PER_LEVEL_3,
    ];

    public function __construct(
        
    ) {}

    public function getCards(): array
    {
        return config('game.cards');
    }

    public function isNewCardAllowed(int $userLevel, int $cardsCount): bool
    {
        return self::CARDS_PER_LEVELS[$userLevel] > $cardsCount;
    }

    public function getCardById(int $cardId): array
    {   
        $cards = $this->getCards();
        $key = array_search($cardId, array_column($cards, 'id'));

        return $cards[$key];
    }

    public function getCardsByIds(array $cardsIds): array
    {   
        if(empty($cardsIds)) {
            return [];
        }

        $cards = $this->getCards();
        $cardsById = [];

        foreach ($cards as $card) {
            if (in_array($card['id'], $cardsIds)) {
                $cardsById[] = $card;
            }
        }

        return $cardsById;
    }

    public function getCardsExceptIds(array $cardsIds): array
    {   
        $cards = $this->getCards();

        if(empty($cardsIds)) {
            return $cards;
        }

        $cardsById = [];
        
        foreach ($cards as $card) {
            if (! in_array($card['id'], $cardsIds)) {
                $cardsById[] = $card;
            }
        }

        return $cardsById;
    }
}
