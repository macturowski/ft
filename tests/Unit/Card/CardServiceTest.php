<?php

namespace Tests\Unit\Card;

use Tests\TestCase;

class CardServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new \App\Http\Services\Card\CardService;
    }

    public function test_that_get_cards_method_return_array_of_cards(): void
    {   
        $this->assertIsArray($this->service->getCards());
    }

    public function test_that_get_cards_method_return_correct_count_of_cards(): void
    {   
        $this->assertEquals(15, count($this->service->getCards()));
    }

    public function test_that_is_new_card_allowed_method_return_false_when_user_cannot_pick_new_card(): void
    {   
        $this->assertFalse($this->service->isNewCardAllowed(2, 10));
    }

    public function test_that_is_new_card_allowed_method_return_true_when_user_can_pick_new_card(): void
    {   
        $this->assertTrue($this->service->isNewCardAllowed(2, 9));
    }

    public function test_that_get_card_by_id_return_single_card_array(): void
    {   
        $this->assertIsArray($this->service->getCardById(2));
    }

    public function test_that_get_card_by_id_return_correct_data(): void
    {   
        $card = $this->service->getCardById(2);

        $this->assertArrayHasKey('id', $card);
        $this->assertArrayHasKey('name', $card);
        $this->assertArrayHasKey('power', $card);
        $this->assertArrayHasKey('image', $card);

        $this->assertIsInt($card['id']);
        $this->assertIsString($card['name']);
        $this->assertIsString($card['image']);
        $this->assertIsInt($card['power']);

        $this->assertEquals(2, $card['id']);
    }

    public function test_that_get_cards_except_ids_return_correct_number_of_cards(): void
    {   
        $cardsIds = [1,5];
        $cards = $this->service->getCardsExceptIds($cardsIds);

        $this->assertCount(13, $cards);
    }

    public function test_that_get_cards_by_ids_return_correct_number_of_cards(): void
    {   
        $cardsIds = [1,5];
        $cards = $this->service->getCardsByIds($cardsIds);

        $this->assertCount(2, $cards);
    }
    public function test_that_get_randon_card_id_from_base_stack_except_id_return_correct_data(): void
    {   
        $cardsIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $cards = $this->service->getRandomCardIdFromBaseStackExceptIds($cardsIds);

        $this->assertEquals(15, $cards);
    }
}
