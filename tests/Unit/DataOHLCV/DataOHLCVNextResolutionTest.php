<?php
/**
 * Date: 31.10.18
 * Time: 16:33
 */

namespace Unit;


use AlecRabbit\DataOHLCV;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

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