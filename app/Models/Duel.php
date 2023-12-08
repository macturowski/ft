<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Duel extends Model
{   
    use HasFactory;

    public const STATUS_ACTIVE = 0;
    public const STATUS_FINISHED = 1;

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

    public function scopeWasWon(Builder $query): void
    {
        $query->where('won', '=', 1);
    }

    public function isClosed(): bool
    {
        return $this->status == 1;
    }

    public function yourPoints(): int
    {
        return $this->details->sum('your_points');
    }

    public function opponentPoints(): int
    {
        return $this->details->sum('opponent_points');
    }

    public function getRounds(): int
    {
        return $this->details->count();
    }

    public function getOpponentCardsIds(): array
    {
        return $this->details->pluck('opponent_card_id')->toArray();
    }

    public function getYourCardsIds(): array
    {
        return $this->details->pluck('your_card_id')->toArray();
    }
}
