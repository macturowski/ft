<?php

namespace Tests\Feature;

use App\Models\DuelDetails;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Duel;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DuelTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);
    }

    public function test_start_duel(): void
    {
        for($i = 0; $i < 5; $i++) {
            UserCard::factory()->create(['card_id' => $i + 1, 'user_id' => $this->user->id]);
        }

        $response = $this->json('POST', 'api/duels');

        $response->assertStatus(200);
    }

    public function test_validation_start_duel_when_user_has_open_duel(): void
    {
        for($i = 0; $i < 5; $i++) {
            Duel::factory()->create(['player_name' => $this->user->name, 'won' => null, 'status' => Duel::STATUS_ACTIVE]);
        }

        $response = $this->json('POST', 'api/duels');

        $response->assertStatus(422);
    }

    public function test_validation_start_duel_when_user_doesnt_have_five_cards(): void
    {
        $response = $this->json('POST', 'api/duels');

        $response->assertStatus(422);
    }

    public function test_duel_active_return_finished_status_when_status_is_finished(): void
    {
        Duel::factory()->create(['player_name' => $this->user->name, 'won' => 1, 'status' => Duel::STATUS_FINISHED]);

        $response = $this->json('GET', 'api/duels/active');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'finished'
        ]);
    }

    public function test_current_duel_data(): void
    {
        $duel = Duel::factory()->create([
            'player_name' => $this->user->name,
            'won' => 1,
            'status' => Duel::STATUS_ACTIVE
        ]);
        DuelDetails::factory()->create(['duel_id' => $duel->id, 'your_points' => 10]);

        $response = $this->json('GET', 'api/duels/active');

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'active']);
        $response->assertJsonFragment(['your_points' => 10]);
    }

    public function test_duel_history_return_correct_data(): void
    {
        Duel::factory()->create(['player_name' => $this->user->name, 'won' => 1, 'status' => Duel::STATUS_FINISHED]);
        Duel::factory()->create(['player_name' => $this->user->name, 'won' => 1, 'status' => Duel::STATUS_FINISHED]);

        $response = $this->json('GET', 'api/duels');

        $response->assertStatus(200);
        $response->assertJsonFragment(['won' => 1]);
        $response->assertJsonCount(2);
    }

    public function test_close_duel_after_five_rounds(): void
    {
        for($i = 0; $i < 5; $i++) {
            UserCard::factory()->create(['card_id' => $i + 1, 'user_id' => $this->user->id]);
        }

        $this->json('POST', 'api/duels');

        for($i = 0; $i < 5; $i++) {
            $this->json('POST', 'api/duels/action');
        }

        $response = $this->json('GET', 'api/duels/active');
        $response->assertStatus(200);    
    }
}
