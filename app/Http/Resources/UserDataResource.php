<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],

            'level' => 1,
            'level_points' => '40/100',
            'cards' => config('game.cards'),
            'new_card_allowed' => true,
        ];
    }
}
