<?php

namespace AlecRabbit\Tests\TWRBCore;

use AlecRabbit\Accessories\Rewindable;
use AlecRabbit\TWRBCore\Counters\TimedCounter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use const AlecRabbit\TWRBCore\Constants\P_01DAY;
use const AlecRabbit\TWRBCore\Constants\P_01HOUR;
use const AlecRabbit\TWRBCore\Constants\P_01MIN;
use const AlecRabbit\TWRBCore\Constants\P_02HOUR;
use const AlecRabbit\TWRBCore\Constants\P_03HOUR;
use const AlecRabbit\TWRBCore\Constants\P_03MIN;
use const AlecRabbit\TWRBCore\Constants\P_04HOUR;
use const AlecRabbit\TWRBCore\Constants\P_05MIN;
use const AlecRabbit\TWRBCore\Constants\P_15MIN;
use const AlecRabbit\TWRBCore\Constants\P_30MIN;
use const AlecRabbit\TWRBCore\Constants\P_45MIN;

class TimedCounterTest extends TestCase
{
    public static $dataGenerator;
    /** @var TimedCounter */
    public $counter;

    public static function setUpBeforeClass(): void
    {
        static::$dataGenerator = new Rewindable(
            function (
                $lines = 5760,
                int $timestamp = 1514764800,
                int $step = 15
            ): ?\Generator {
                while ($lines-- > 0) {
                    yield $timestamp;
                    $timestamp += $step;
                }
            }
        );
    }

    /** @test */
    public function instance(): void
    {
        $this->assertEquals(DEFAULT_NAME, $this->counter->getName());

        $this->counter->setName('new');
        $this->assertEquals('new', $this->counter->getName());
        $this->assertEquals([], $this->counter->getRawData());
        $this->assertEquals([], $this->counter->getDataArray());
        $this->counter->add();
        $expected =
            [
                P_01MIN => 1,
                P_03MIN => 1,
                P_05MIN => 1,
                P_15MIN => 1,
                P_30MIN => 1,
                P_45MIN => 1,
                P_01HOUR => 1,
                P_02HOUR => 1,
                P_03HOUR => 1,
                P_04HOUR => 1,
                P_01DAY => 1,
            ];
        $this->assertEquals($expected, $this->counter->getDataArray());
        $this->counter->add();
        $expected =
            [
                P_01MIN => 2,
                P_03MIN => 2,
                P_05MIN => 2,
                P_15MIN => 2,
                P_30MIN => 2,
                P_45MIN => 2,
                P_01HOUR => 2,
                P_02HOUR => 2,
                P_03HOUR => 2,
                P_04HOUR => 2,
                P_01DAY => 2,
            ];
        $this->assertEquals($expected, $this->counter->getDataArray());
    }

    /** @test */
    public function instanceRelative(): void
    {
        $this->counter->enableRelativeMode();
        $this->counter->add(1542804418);
        $expected =
            [
                P_01MIN => [1542804418 => 1],
                P_03MIN => [1542804360 => 1],
                P_05MIN => [1542804360 => 1],
                P_15MIN => [1542804300 => 1],
                P_30MIN => [1542804300 => 1],
                P_45MIN => [1542804300 => 1],
                P_01HOUR => [1542804300 => 1],
                P_02HOUR => [1542804300 => 1],
                P_03HOUR => [1542804300 => 1],
                P_04HOUR => [1542804300 => 1],
                P_01DAY => [1542801600 => 1],
            ];
        $this->assertEquals($expected, $this->counter->getRawData());

        $expected =
            [
                P_01MIN => 1,
                P_03MIN => 1,
                P_05MIN => 1,
                P_15MIN => 1,
                P_30MIN => 1,
                P_45MIN => 1,
                P_01HOUR => 1,
                P_02HOUR => 1,
                P_03HOUR => 1,
                P_04HOUR => 1,
                P_01DAY => 1,
            ];
        $this->assertEquals($expected, $this->counter->getDataArray());
        $expectedObject = new \stdClass();
        $expectedObject->{P_01MIN} = 1;
        $expectedObject->{P_03MIN} = 1;
        $expectedObject->{P_05MIN} = 1;
        $expectedObject->{P_15MIN} = 1;
        $expectedObject->{P_30MIN} = 1;
        $expectedObject->{P_45MIN} = 1;
        $expectedObject->{P_01HOUR} = 1;
        $expectedObject->{P_02HOUR} = 1;
        $expectedObject->{P_03HOUR} = 1;
        $expectedObject->{P_04HOUR} = 1;
        $expectedObject->{P_01DAY} = 1;
        $this->assertEquals($expectedObject, $this->counter->getDataObject());
        $this->counter->add(1542804418);
        $expected =
            [
                P_01MIN => 2,
                P_03MIN => 2,
                P_05MIN => 2,
                P_15MIN => 2,
                P_30MIN => 2,
                P_45MIN => 2,
                P_01HOUR => 2,
                P_02HOUR => 2,
                P_03HOUR => 2,
                P_04HOUR => 2,
                P_01DAY => 2,
            ];
        $this->assertEquals($expected, $this->counter->getDataArray());
        $expectedObject->{P_01MIN} = 2;
        $expectedObject->{P_03MIN} = 2;
        $expectedObject->{P_05MIN} = 2;
        $expectedObject->{P_15MIN} = 2;
        $expectedObject->{P_30MIN} = 2;
        $expectedObject->{P_45MIN} = 2;
        $expectedObject->{P_01HOUR} = 2;
        $expectedObject->{P_02HOUR} = 2;
        $expectedObject->{P_03HOUR} = 2;
        $expectedObject->{P_04HOUR} = 2;
        $expectedObject->{P_01DAY} = 2;
        $this->assertEquals($expectedObject, $this->counter->getDataObject());
        $this->counter->add(1542804420);
        $expected =
            [
                P_01MIN => 3,
                P_03MIN => 3,
                P_05MIN => 3,
                P_15MIN => 3,
                P_30MIN => 3,
                P_45MIN => 3,
                P_01HOUR => 3,
                P_02HOUR => 3,
                P_03HOUR => 3,
                P_04HOUR => 3,
                P_01DAY => 3,
            ];
        $this->assertEquals($expected, $this->counter->getDataArray(true));
        $this->assertEquals([], $this->counter->getDataArray());
        $this->assertEquals([], $this->counter->getRawData());
    }

    /** @test */
    public function fill(): void
    {
        $this->counter = new TimedCounter(
            null, [ // Period => GroupBy
                P_01MIN => P_01MIN,
                P_03MIN => P_03MIN,
                P_05MIN => P_05MIN,
                P_15MIN => P_15MIN,
                P_30MIN => P_30MIN,
                P_45MIN => P_45MIN,
                P_01HOUR => P_01HOUR,
                P_02HOUR => P_02HOUR,
                P_03HOUR => P_03HOUR,
                P_04HOUR => P_04HOUR,
                P_01DAY => P_01DAY,
            ]
        );

        $this->counter->enableRelativeMode();
        foreach (static::$dataGenerator as $timestamp) {
            $this->counter->add($timestamp);
        }
        $expected =
            [
                P_01MIN => [1514851140 => 4],
                P_03MIN => [1514851020 => 12],
                P_05MIN => [1514850900 => 20],
                P_15MIN => [1514850300 => 60],
                P_30MIN => [1514849400 => 120],
                P_45MIN => [1514848500 => 180],
                P_01HOUR => [1514847600 => 240],
                P_02HOUR => [1514844000 => 480],
                P_03HOUR => [1514840400 => 720],
                P_04HOUR => [1514836800 => 960],
                P_01DAY => [1514764800 => 5760],
            ];
        $this->assertEquals($expected, $this->counter->getRawData());

        $expected =
            [
                P_01MIN => 4,
                P_03MIN => 12,
                P_05MIN => 20,
                P_15MIN => 60,
                P_30MIN => 120,
                P_45MIN => 180,
                P_01HOUR => 240,
                P_02HOUR => 480,
                P_03HOUR => 720,
                P_04HOUR => 960,
                P_01DAY => 5760,
            ];

        $this->assertEquals($expected, $this->counter->getDataArray());
        $expectedObject = new \stdClass();
        $expectedObject->{P_01MIN} = 4;
        $expectedObject->{P_03MIN} = 12;
        $expectedObject->{P_05MIN} = 20;
        $expectedObject->{P_15MIN} = 60;
        $expectedObject->{P_30MIN} = 120;
        $expectedObject->{P_45MIN} = 180;
        $expectedObject->{P_01HOUR} = 240;
        $expectedObject->{P_02HOUR} = 480;
        $expectedObject->{P_03HOUR} = 720;
        $expectedObject->{P_04HOUR} = 960;
        $expectedObject->{P_01DAY} = 5760;

        $this->assertEquals($expectedObject, $this->counter->getDataObject(true));
        $expectedObject = new \stdClass();
        $this->assertEquals($expectedObject, $this->counter->getDataObject());
    }

    /**
     * @test
     * @dataProvider toFillOnePeriod
     * @param $periods
     * @param $expectedRaw
     * @param $expected
     */
    public function fillOnePeriod($periods, $expectedRaw, $expected): void
    {
        $this->counter = new TimedCounter(null, $periods);

        $this->counter->enableRelativeMode();
        foreach (static::$dataGenerator as $timestamp) {
            $this->counter->add($timestamp);
        }
        $this->assertEquals($expectedRaw, $this->counter->getRawData());

        $this->assertEquals($expected, $this->counter->getDataArray());
    }

    /** @test */
    public function fillNormal(): void
    {
        $this->counter->enableRelativeMode();
        foreach (static::$dataGenerator as $timestamp) {
            $this->counter->add($timestamp);
        }
        $expected =
            [
                P_01MIN => [
                    1514851140 => 1,
                    1514851155 => 1,
                    1514851170 => 1,
                    1514851185 => 1,
                ],
                P_03MIN => [
                    1514851020 => 4,
                    1514851080 => 4,
                    1514851140 => 4,
                ],
                P_05MIN => [
                    1514850900 => 4,
                    1514850960 => 4,
                    1514851020 => 4,
                    1514851080 => 4,
                    1514851140 => 4,
                ],
                P_15MIN => [
                    1514850300 => 20,
                    1514850600 => 20,
                    1514850900 => 20,
                ],
                P_30MIN => [
                    1514849400 => 20,
                    1514849700 => 20,
                    1514850000 => 20,
                    1514850300 => 20,
                    1514850600 => 20,
                    1514850900 => 20,
                ],
                P_45MIN => [
                    1514848500 => 20,
                    1514848800 => 20,
                    1514849100 => 20,
                    1514849400 => 20,
                    1514849700 => 20,
                    1514850000 => 20,
                    1514850300 => 20,
                    1514850600 => 20,
                    1514850900 => 20,
                ],
                P_01HOUR => [
                    1514847600 => 60,
                    1514848500 => 60,
                    1514849400 => 60,
                    1514850300 => 60,
                ],
                P_02HOUR => [
                    1514844000 => 60,
                    1514844900 => 60,
                    1514845800 => 60,
                    1514846700 => 60,
                    1514847600 => 60,
                    1514848500 => 60,
                    1514849400 => 60,
                    1514850300 => 60,

                ],
                P_03HOUR => [
                    1514840400 => 60,
                    1514841300 => 60,
                    1514842200 => 60,
                    1514843100 => 60,
                    1514844000 => 60,
                    1514844900 => 60,
                    1514845800 => 60,
                    1514846700 => 60,
                    1514847600 => 60,
                    1514848500 => 60,
                    1514849400 => 60,
                    1514850300 => 60,
                ],
                P_04HOUR => [
                    1514836800 => 60,
                    1514837700 => 60,
                    1514838600 => 60,
                    1514839500 => 60,
                    1514840400 => 60,
                    1514841300 => 60,
                    1514842200 => 60,
                    1514843100 => 60,
                    1514844000 => 60,
                    1514844900 => 60,
                    1514845800 => 60,
                    1514846700 => 60,
                    1514847600 => 60,
                    1514848500 => 60,
                    1514849400 => 60,
                    1514850300 => 60,
                ],
                P_01DAY => [
                    1514764800 => 240,
                    1514768400 => 240,
                    1514772000 => 240,
                    1514775600 => 240,
                    1514779200 => 240,
                    1514782800 => 240,
                    1514786400 => 240,
                    1514790000 => 240,
                    1514793600 => 240,
                    1514797200 => 240,
                    1514800800 => 240,
                    1514804400 => 240,
                    1514808000 => 240,
                    1514811600 => 240,
                    1514815200 => 240,
                    1514818800 => 240,
                    1514822400 => 240,
                    1514826000 => 240,
                    1514829600 => 240,
                    1514833200 => 240,
                    1514836800 => 240,
                    1514840400 => 240,
                    1514844000 => 240,
                    1514847600 => 240,
                ],
            ];
        $this->assertEquals($expected, $this->counter->getRawData());

        $expected =
            [
                P_01MIN => 4,
                P_03MIN => 12,
                P_05MIN => 20,
                P_15MIN => 60,
                P_30MIN => 120,
                P_45MIN => 180,
                P_01HOUR => 240,
                P_02HOUR => 480,
                P_03HOUR => 720,
                P_04HOUR => 960,
                P_01DAY => 5760,
            ];

        $this->assertEquals($expected, $this->counter->getDataArray());
        $expectedObject = new \stdClass();
        $expectedObject->{P_01MIN} = 4;
        $expectedObject->{P_03MIN} = 12;
        $expectedObject->{P_05MIN} = 20;
        $expectedObject->{P_15MIN} = 60;
        $expectedObject->{P_30MIN} = 120;
        $expectedObject->{P_45MIN} = 180;
        $expectedObject->{P_01HOUR} = 240;
        $expectedObject->{P_02HOUR} = 480;
        $expectedObject->{P_03HOUR} = 720;
        $expectedObject->{P_04HOUR} = 960;
        $expectedObject->{P_01DAY} = 5760;

        $this->assertEquals($expectedObject, $this->counter->getDataObject());
    }

    public function toFillOnePeriod(): array
    {
        return [
            [[P_01MIN => P_01MIN,], [P_01MIN => [1514851140 => 4]], [P_01MIN => 4,]],
            [[P_03MIN => P_03MIN,], [P_03MIN => [1514851020 => 12]], [P_03MIN => 12,]],
            [[P_05MIN => P_05MIN,], [P_05MIN => [1514850900 => 20]], [P_05MIN => 20,]],
            [[P_15MIN => P_15MIN,], [P_15MIN => [1514850300 => 60]], [P_15MIN => 60,]],
            [[P_30MIN => P_30MIN,], [P_30MIN => [1514849400 => 120]], [P_30MIN => 120,]],
            [[P_45MIN => P_45MIN,], [P_45MIN => [1514848500 => 180]], [P_45MIN => 180,]],
            [[P_01HOUR => P_01HOUR,], [P_01HOUR => [1514847600 => 240]], [P_01HOUR => 240,]],
            [[P_02HOUR => P_02HOUR,], [P_02HOUR => [1514844000 => 480]], [P_02HOUR => 480,]],
            [[P_03HOUR => P_03HOUR,], [P_03HOUR => [1514840400 => 720]], [P_03HOUR => 720,]],
            [[P_04HOUR => P_04HOUR,], [P_04HOUR => [1514836800 => 960]], [P_04HOUR => 960,]],
            [[P_01DAY => P_01DAY,], [P_01DAY => [1514764800 => 5760]], [P_01DAY => 5760,]],
        ];
    }

    protected function setUp():void
    {
        $this->counter = new TimedCounter(null);
    }

    protected function tearDown(): void
    {
        unset($this->counter);
    }
}
