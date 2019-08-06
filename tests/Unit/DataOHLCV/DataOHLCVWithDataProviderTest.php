<?php

namespace Unit;

use AlecRabbit\Accessories\Circular;
use AlecRabbit\DataOHLCV;
use AlecRabbit\Accessories\Rewindable;
use PHPUnit\Framework\TestCase;
use Tests\Unit\DataProviders\CommonTrades;

class DataOHLCVWithDataProviderTest extends TestCase
{
    /** @var DataOHLCV */
    protected static $object;
    /** @var Rewindable */
    private static $data;

    public static function setUpBeforeClass()
    {
        $pair = 'btc_usd';

        static::$data = new Rewindable(
            [CommonTrades::class, 'generator'],
            new Circular([T_ASK, T_BID]),
            new Circular([$pair]),
            new Circular(
                [10000.0022, 10000.0001, 10000.2022, 10000.0505]
            ),
            new Circular([0.001, 0.0001, 0.00001, 0.000001])
        );

        static::$object = new DataOHLCV($pair, 1440);
        foreach (static::$data as $trade) {
            static::$object->addTrade($trade);
        }
    }

    public static function tearDownAfterClass(): void
    {
        static::$object = null;
    }

    /**
     * @test
     */
    public function checkVolumes(): void
    {
        foreach ($this->dataToCheckVolumes() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getVolumes($resolution));
        }
    }

    private function dataToCheckVolumes(): array
    {
        return [
            RESOLUTION_01MIN => array_fill(0, 1439, 0.001111),
            RESOLUTION_03MIN => array_fill(0, 479, 0.003333),
            RESOLUTION_05MIN => array_fill(0, 287, 0.005555),
            RESOLUTION_15MIN => array_fill(0, 95, 0.016665),
            RESOLUTION_30MIN => array_fill(0, 47, 0.03333),
            RESOLUTION_45MIN => array_fill(0, 31, 0.049995),
            RESOLUTION_01HOUR => array_fill(0, 23, 0.06666),
            RESOLUTION_02HOUR => array_fill(0, 11, 0.13332),
            RESOLUTION_03HOUR => array_fill(0, 7, 0.19998),
            RESOLUTION_04HOUR => array_fill(0, 5, 0.26664),
            RESOLUTION_01DAY => array_fill(0, 0, 1.59984),
        ];
    }

    /**
     * @test
     */
    public function checkOpens(): void
    {
        foreach ($this->dataToCheckOpens() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getOpens($resolution));
        }
    }

    private function dataToCheckOpens(): array
    {
        return [
            RESOLUTION_01MIN => array_fill(0, 1439, 10000.0022),
            RESOLUTION_03MIN => array_fill(0, 479, 10000.0022),
            RESOLUTION_05MIN => array_fill(0, 287, 10000.0022),
            RESOLUTION_15MIN => array_fill(0, 95, 10000.0022),
            RESOLUTION_30MIN => array_fill(0, 47, 10000.0022),
            RESOLUTION_45MIN => array_fill(0, 31, 10000.0022),
            RESOLUTION_01HOUR => array_fill(0, 23, 10000.0022),
            RESOLUTION_02HOUR => array_fill(0, 11, 10000.0022),
            RESOLUTION_03HOUR => array_fill(0, 7, 10000.0022),
            RESOLUTION_04HOUR => array_fill(0, 5, 10000.0022),
            RESOLUTION_01DAY => array_fill(0, 0, 10000.0022),
        ];
    }

    /**
     * @test
     */
    public function checkHighs(): void
    {
        foreach ($this->dataToCheckHighs() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getHighs($resolution));
        }
    }

    private function dataToCheckHighs(): array
    {
        return [
            RESOLUTION_01MIN => array_fill(0, 1439, 10000.2022),
            RESOLUTION_03MIN => array_fill(0, 479, 10000.2022),
            RESOLUTION_05MIN => array_fill(0, 287, 10000.2022),
            RESOLUTION_15MIN => array_fill(0, 95, 10000.2022),
            RESOLUTION_30MIN => array_fill(0, 47, 10000.2022),
            RESOLUTION_45MIN => array_fill(0, 31, 10000.2022),
            RESOLUTION_01HOUR => array_fill(0, 23, 10000.2022),
            RESOLUTION_02HOUR => array_fill(0, 11, 10000.2022),
            RESOLUTION_03HOUR => array_fill(0, 7, 10000.2022),
            RESOLUTION_04HOUR => array_fill(0, 5, 10000.2022),
            RESOLUTION_01DAY => array_fill(0, 0, 10000.2022),
        ];
    }

    /**
     * @test
     */
    public function checkLows(): void
    {
        foreach ($this->dataToCheckLows() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getLows($resolution));
        }
    }

    private function dataToCheckLows(): array
    {
        return [
            RESOLUTION_01MIN => array_fill(0, 1439, 10000.0001),
            RESOLUTION_03MIN => array_fill(0, 479, 10000.0001),
            RESOLUTION_05MIN => array_fill(0, 287, 10000.0001),
            RESOLUTION_15MIN => array_fill(0, 95, 10000.0001),
            RESOLUTION_30MIN => array_fill(0, 47, 10000.0001),
            RESOLUTION_45MIN => array_fill(0, 31, 10000.0001),
            RESOLUTION_01HOUR => array_fill(0, 23, 10000.0001),
            RESOLUTION_02HOUR => array_fill(0, 11, 10000.0001),
            RESOLUTION_03HOUR => array_fill(0, 7, 10000.0001),
            RESOLUTION_04HOUR => array_fill(0, 5, 10000.0001),
            RESOLUTION_01DAY => array_fill(0, 0, 10000.0001),
        ];
    }

    /**
     * @test
     */
    public function checkCloses(): void
    {
        foreach ($this->dataToCheckCloses() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getCloses($resolution));
        }
    }

    private function dataToCheckCloses(): array
    {
        return [
            RESOLUTION_01MIN => array_fill(0, 1439, 10000.0505),
            RESOLUTION_03MIN => array_fill(0, 479, 10000.0505),
            RESOLUTION_05MIN => array_fill(0, 287, 10000.0505),
            RESOLUTION_15MIN => array_fill(0, 95, 10000.0505),
            RESOLUTION_30MIN => array_fill(0, 47, 10000.0505),
            RESOLUTION_45MIN => array_fill(0, 31, 10000.0505),
            RESOLUTION_01HOUR => array_fill(0, 23, 10000.0505),
            RESOLUTION_02HOUR => array_fill(0, 11, 10000.0505),
            RESOLUTION_03HOUR => array_fill(0, 7, 10000.0505),
            RESOLUTION_04HOUR => array_fill(0, 5, 10000.0505),
            RESOLUTION_01DAY => array_fill(0, 0, 10000.0505),
        ];
    }

    /**
     * @test
     */
    public function checkHasPeriods(): void
    {
        foreach ($this->dataToCheckHasPeriods() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->hasPeriods($resolution, 1));
        }
    }

    private function dataToCheckHasPeriods(): array
    {
        return [
            RESOLUTION_01MIN => true,
            RESOLUTION_03MIN => true,
            RESOLUTION_05MIN => true,
            RESOLUTION_15MIN => true,
            RESOLUTION_30MIN => true,
            RESOLUTION_45MIN => true,
            RESOLUTION_01HOUR => true,
            RESOLUTION_02HOUR => true,
            RESOLUTION_03HOUR => true,
            RESOLUTION_04HOUR => true,
            RESOLUTION_01DAY => false,
        ];
    }

    /**
     * @test
     */
    public function checkLastHigh(): void
    {
        foreach ($this->dataToCheckLastHigh() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getLastHigh($resolution), "Resolution: {$resolution}");
        }
    }

    private function dataToCheckLastHigh(): array
    {
        return [
            RESOLUTION_01MIN => 10000.2022,
            RESOLUTION_03MIN => 10000.2022,
            RESOLUTION_05MIN => 10000.2022,
            RESOLUTION_15MIN => 10000.2022,
            RESOLUTION_30MIN => 10000.2022,
            RESOLUTION_45MIN => 10000.2022,
            RESOLUTION_01HOUR => 10000.2022,
            RESOLUTION_02HOUR => 10000.2022,
            RESOLUTION_03HOUR => 10000.2022,
            RESOLUTION_04HOUR => 10000.2022,
            RESOLUTION_01DAY => null,
        ];
    }

    /**
     * @test
     */
    public function checkLastLow(): void
    {
        foreach ($this->dataToCheckLastLow() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getLastLow($resolution), "Resolution: {$resolution}");
        }
    }

    private function dataToCheckLastLow(): array
    {
        return [
            RESOLUTION_01MIN => 10000.0001,
            RESOLUTION_03MIN => 10000.0001,
            RESOLUTION_05MIN => 10000.0001,
            RESOLUTION_15MIN => 10000.0001,
            RESOLUTION_30MIN => 10000.0001,
            RESOLUTION_45MIN => 10000.0001,
            RESOLUTION_01HOUR => 10000.0001,
            RESOLUTION_02HOUR => 10000.0001,
            RESOLUTION_03HOUR => 10000.0001,
            RESOLUTION_04HOUR => 10000.0001,
            RESOLUTION_01DAY => null,
        ];
    }

    /**
     * @test
     */
    public function checkLastClose(): void
    {
        foreach ($this->dataToCheckLastClose() as $resolution => $expected) {
            $this->assertEquals($expected, static::$object->getLastClose($resolution), "Resolution: {$resolution}");
        }
    }

    private function dataToCheckLastClose(): array
    {
        return [
            RESOLUTION_01MIN => 10000.0505,
            RESOLUTION_03MIN => 10000.0505,
            RESOLUTION_05MIN => 10000.0505,
            RESOLUTION_15MIN => 10000.0505,
            RESOLUTION_30MIN => 10000.0505,
            RESOLUTION_45MIN => 10000.0505,
            RESOLUTION_01HOUR => 10000.0505,
            RESOLUTION_02HOUR => 10000.0505,
            RESOLUTION_03HOUR => 10000.0505,
            RESOLUTION_04HOUR => 10000.0505,
            RESOLUTION_01DAY => null,
        ];
    }
}