<?php

namespace AlecRabbit\Tests\TWRBCore;

use AlecRabbit\TWRBCore\DataOHLCV;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_01DAY;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_01HOUR;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_01MIN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_02HOUR;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_03HOUR;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_03MIN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_04HOUR;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_05MIN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_15MIN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_30MIN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_45MIN;

class DataOHLCVNextResolutionTest extends TestCase
{
    /**
     * @test
     * @dataProvider forNextResolution
     * @param $expected

     * @param $param
     * @throws \ReflectionException
     */
    public function nextResolution($expected, $param): void
    {
        $method = new ReflectionMethod(DataOHLCV::class, 'nextResolution');
        $method->setAccessible(true);

        $object = new DataOHLCV('btc_usd', 500);

        $this->assertEquals($expected, $method->invoke($object, $param));
        unset($method, $object);
    }

    public function forNextResolution(): array
    {
        return [
            [RESOLUTION_03MIN, RESOLUTION_01MIN],
            [RESOLUTION_05MIN, RESOLUTION_03MIN],
            [RESOLUTION_15MIN, RESOLUTION_05MIN],
            [RESOLUTION_30MIN, RESOLUTION_15MIN],
            [RESOLUTION_45MIN, RESOLUTION_30MIN],
            [RESOLUTION_01HOUR, RESOLUTION_45MIN],
            [RESOLUTION_02HOUR, RESOLUTION_01HOUR],
            [RESOLUTION_03HOUR, RESOLUTION_02HOUR],
            [RESOLUTION_04HOUR, RESOLUTION_03HOUR],
            [RESOLUTION_01DAY, RESOLUTION_04HOUR],
            [false, RESOLUTION_01DAY],
        ];
    }
}
