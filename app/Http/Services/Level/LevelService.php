<?php

namespace App\Http\Services\Level;

class LevelService
{   
    public const LEVEL_1 = 1;
    public const LEVEL_2 = 2;
    public const LEVEL_3 = 3;
    private const SINGLE_WIN_POINTS = 20;
    private const POINTS_TO_LEVEL_2 = 100;
    private const POINTS_TO_LEVEL_3 =  160;

    public function getLevel(int $wins): mixed
    {
        $points = $this->getPoints($wins);

        if($points >= self::POINTS_TO_LEVEL_3) {
            return self::LEVEL_3;
        }

        if($points >= self::POINTS_TO_LEVEL_2) {
            return self::LEVEL_2;
        }

        return self::LEVEL_1;
    }

    public function getLevelPoints(int $wins): string
    {
        $level = $this->getLevel($wins);
        $points = $this->getPoints($wins);

        if($level === self::LEVEL_1) {
            return $points . ' / ' . self::POINTS_TO_LEVEL_2;
        }

        if($level === self::LEVEL_2) {
            return $points . ' / ' . self::POINTS_TO_LEVEL_3;
        }

        return $points;
    }

    private function getPoints(int $wins): int
    {
        return $wins * self::SINGLE_WIN_POINTS;
    }
}
