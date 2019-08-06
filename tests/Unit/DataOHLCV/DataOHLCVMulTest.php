<?php
/**
 * Date: 31.10.18
 * Time: 16:33
 */

namespace Unit;

use AlecRabbit\DataOHLCV;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class DataOHLCVMulTest extends TestCase
{
    /**
     * @test
     * @dataProvider mulDataProvider
     * @param $expected
     * @param $param1
     * @param $param2
     * @param $coefficient
     * @throws \ReflectionException
     */
    public function mul($expected, $param1, $param2, $coefficient): void
    {
        $method = new ReflectionMethod(DataOHLCV::class, 'mul');
        $method->setAccessible(true);

        $object = new DataOHLCV('btc_usd', 500, $coefficient);

        $this->assertEquals($expected, $method->invoke($object, $param1, $param2));
        unset($method, $object);
    }

    public function mulDataProvider(): array
    {
        return [
            [1, 1, true, 1],
            [10, 1, true, 10],
            [5, 0.5, true, 10],
            [1, 1, false, 1],
            [1, 1, false, 10],
        ];
    }

    /**
     * @test
     * @dataProvider mulArrDataProvider
     * @param $expected
     * @param $param1
     * @param $param2
     * @param $coefficient
     * @throws \ReflectionException
     */
    public function mulArr($expected, $param1, $param2, $coefficient): void
    {
        $method = new \ReflectionMethod(DataOHLCV::class, 'mulArr');
        $method->setAccessible(true);

        $object = new DataOHLCV('btc_usd', 500, $coefficient);

        $this->assertEquals($expected, $method->invoke($object, $param1, $param2));
        unset($method, $object);
    }

    public function mulArrDataProvider(): array
    {
        return [
            [[1], [1], true, 1],
            [[2, 4, 6], [1, 2, 3], true, 2],
            [[1, 2, 3], [1, 2, 3], false, 2],
        ];
    }


}