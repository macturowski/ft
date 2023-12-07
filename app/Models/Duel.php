<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Duel extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'opponent_name',
        'won',
        'user_id',
        'status',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(DuelDetails::class);
    }

    public function isClosed(): bool
    {
        return $this->status == 1;
    }
}
