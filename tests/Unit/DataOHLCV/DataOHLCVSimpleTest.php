<?php
/**
 * Date: 31.10.18
 * Time: 16:33
 */

namespace Unit;


use AlecRabbit\DataOHLCV;
use AlecRabbit\Structures\Trade;
use PHPUnit\Framework\TestCase;

class DataOHLCVSimpleTest extends TestCase
{
    /** @var DataOHLCV */
    protected $ohlcv;

    public function setUp()
    {
        $pair = 'btc_usd';
        $this->ohlcv = new DataOHLCV($pair, 500);
        foreach ($this->simpleData() as $item) {
            [$timestamp, $type, $price, $amount] = $item;
            $this->ohlcv->addTrade(new Trade($type, $pair, $price, $amount, $timestamp));
        }
    }

    public function simpleData(): array
    {
        return [
            [1512570380, T_BID, 12820.7, 0.0538594],
            [1512571380, T_BID, 12820.7, 0.00596801],
            [1512572380, T_BID, 12821.3, 0.19551],
            [1512573380, T_BID, 12807.6, 0.0464246],
            [1512574380, T_BID, 12793, 0.0538914],
            [1512575380, T_ASK, 12792, 0.0475634],
            [1512576380, T_BID, 12793, 0.0499],
            [1512577380, T_BID, 12810.2, 0.171179],
            [1512578380, T_BID, 12810.2, 0.0223277],
            [1512579380, T_BID, 12818.5, 0.00210018],
            [1512585380, T_ASK, 12792, 0.0475634],
            [1512586380, T_BID, 12793, 0.0499],
            [1512587380, T_BID, 12810.2, 0.171179],
            [1512588380, T_BID, 12810.2, 0.0223277],
            [1512589380, T_BID, 12818.5, 0.00210018],
            [1513589380, T_BID, 12818.5, 0.00210018],
        ];
    }

    /**
     * @test
     * @dataProvider simpleDataProvider
     * @param $expected
     * @param $resolution
     */
    public function simpleDataCheck($expected, $resolution): void
    {
        $this->assertEquals($expected, $this->ohlcv->getTimestamps($resolution));
    }

    public function simpleDataProvider(): array
    {
        return [
            [
                [
                    1512570360,
                    1512571380,
                    1512572340,
                    1512573360,
                    1512574380,
                    1512575340,
                    1512576360,
                    1512577380,
                    1512578340,
                    1512579360,
                    1512585360,
                    1512586380,
                    1512587340,
                    1512588360,
                    1512589380,
                ],
                RESOLUTION_01MIN
            ],
            [
                [
                    1512570240,
                    1512571320,
                    1512572220,
                    1512573300,
                    1512574380,
                    1512575280,
                    1512576360,
                    1512577260,
                    1512578340,
                    1512579240,
                    1512585360,
                    1512586260,
                    1512587340,
                    1512588240,
                    1512589320,
                ],
                RESOLUTION_03MIN
            ],
            [
                [
                    1512570300,
                    1512571200,
                    1512572100,
                    1512573300,
                    1512574200,
                    1512575100,
                    1512576300,
                    1512577200,
                    1512578100,
                    1512579300,
                    1512585300,
                    1512586200,
                    1512587100,
                    1512588300,
                    1512589200,
                ],
                RESOLUTION_05MIN
            ],
            [
                [
                    1512569700,
                    1512570600,
                    1512571500,
                    1512573300,
                    1512574200,
                    1512575100,
                    1512576000,
                    1512576900,
                    1512577800,
                    1512578700,
                    1512585000,
                    1512585900,
                    1512586800,
                    1512587700,
                    1512588600,
                ],
                RESOLUTION_15MIN
            ],
            [
                [
                    1512568800,
                    1512570600,
                    1512572400,
                    1512574200,
                    1512576000,
                    1512577800,
                    1512585000,
                    1512586800,
                    1512588600,
                ],
                RESOLUTION_30MIN
            ],
            [
                [
                    1512569700,
                    1512572400,
                    1512575100,
                    1512577800,
                    1512583200,
                    1512585900,
                    1512588600,
                ],
                RESOLUTION_45MIN
            ],
            [
                [
                    1512568800,
                    1512572400,
                    1512576000,
                    1512583200,
                    1512586800,
                ],
                RESOLUTION_01HOUR
            ],
            [[1512568800, 1512576000, 1512583200], RESOLUTION_02HOUR],
            [[1512561600, 1512572400, 1512583200], RESOLUTION_03HOUR],
            [[1512561600, 1512576000], RESOLUTION_04HOUR],
            [[1512518400], RESOLUTION_01DAY],
        ];
    }


}