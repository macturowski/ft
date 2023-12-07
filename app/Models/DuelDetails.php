<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuelDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'round',
        'your_points',
        'opponent_points',
        'your_card_id',
        'opponent_card_id',
        'duel_id',
    ];
}
