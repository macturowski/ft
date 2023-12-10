<?php

namespace Tests\Unit\Level;

use PHPUnit\Framework\TestCase;

class LevelServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new \App\Http\Services\Level\LevelService;
    }

    public function test_that_method_get_level_points_return_correct_value(): void
    {
        $points = $this->service->getLevelPoints(2);

        $this->assertEquals('40 / 100', $points);
    }

    public function test_that_method_get_level_return_correct_value(): void
    {
        $level = $this->service->getLevel(10);

        $this->assertEquals(3, $level);
    }
}
