<?php

namespace Tests\Feature;

use App\Models\Duel;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_validation_user_login(): void
    {
        $response = $this->json('POST', 'api/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_login(): void
    {
        $response = $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_user_logout(): void
    {
        $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->json('POST', 'api/logout');

        $response->assertStatus(200);
    }

    public function test_new_user_can_pick_five_card(): void
    {
        $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->json('POST', 'api/cards');
        $response->assertStatus(200);

        $response = $this->json('POST', 'api/cards');
        $response->assertStatus(200);

        $response = $this->json('POST', 'api/cards');
        $response->assertStatus(200);

        $response = $this->json('POST', 'api/cards');
        $response->assertStatus(200);

        $response = $this->json('POST', 'api/cards');
        $response->assertStatus(200);
    }

    public function test_user_with_new_level_can_pick_a_new_card(): void
    {
        for($i = 0; $i < 5; $i++) {
            Duel::factory()->create(['player_name' => $this->user->name, 'won' => 1]);
        }

        for($i = 0; $i < 5; $i++) {
            UserCard::factory()->create(['card_id' => $i + 1, 'user_id' => $this->user->id]);
        }

        $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->json('POST', 'api/cards');

        $response->assertStatus(200);
    }

    public function test_validation_user_pick_card_when_user_can_not_pick_a_new_card(): void
    {
        for($i = 0; $i < 4; $i++) {
            Duel::factory()->create(['player_name' => $this->user->name, 'won' => 1]);
        }

        for($i = 0; $i < 5; $i++) {
            UserCard::factory()->create(['card_id' => $i + 1, 'user_id' => $this->user->id]);
        }

        $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->json('POST', 'api/cards');

        $response->assertStatus(422);
    }
}
