<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        //napisane z pomocą copilot
        return [
            [0.0, 32.0],
            [-100.0, -148.0],
            [100.0, 212.0],
            [0.5, 32.9],
            [-0.5, 31.1],
            [10.0, 50.0],
            [-40.0, -40.0],
            [37.0, 98.6],
            [20.0, 68.0],
            [25.5, 77.9],
        ];
    }
    // napisane z pomocą copilot
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(
        float $celsius,
        float $expectedFahrenheit
    ): void {
        $forecast = new Forecast();

        $forecast->setTemperature($celsius);

        $this->assertEqualsWithDelta(
            $expectedFahrenheit,
            $forecast->getFahrenheit(),
            0.00001
        );
    }
}