<?php

namespace AlecRabbit\Tests\TWRBCore;

use AlecRabbit\TWRBCore\DataOHLCV;
use PHPUnit\Framework\TestCase;

class DataOHLCVBasicTest extends TestCase
{
    /**
     * @test
     * @dataProvider sizes
     * @param $expected
     * @param $actual
     */
    public function basicSize($expected, $actual): void
    {
        $ohlcv = new DataOHLCV('btc_usd', $actual);
        $this->assertEquals($expected, $ohlcv->getSize());
    }

    /**
     * @test
     * @dataProvider pairs
     * @param $expected
     * @param $actual
     */
    public function basicPair($expected, $actual): void
    {
        $ohlcv = new DataOHLCV($actual, 10);
        $this->assertEquals($expected, $ohlcv->getPair());
    }

    public function sizes(): array
    {
        return [
            [500, 500],
            [50, 50],
            [10, 0],
            [999, 999],
            [1000, 1000],
            [1001, 1001],
            [1440, 1441],
            [1440, 10000],
        ];
    }

    public function pairs(): array
    {
        return [
            ['btc_usd', 'btc_usd',],
            ['btc_eur', 'btc_eur',],
            ['btc_rur', 'btc_rur',],
            ['ltc_btc', 'ltc_btc',],
            ['ltc_usd', 'ltc_usd',],
            ['ltc_rur', 'ltc_rur',],
            ['nmc_btc', 'nmc_btc',],
            ['usd_rur', 'usd_rur',],
            ['eur_usd', 'eur_usd',],
            ['nvc_btc', 'nvc_btc',],
            ['ppc_btc', 'ppc_btc',],
            ['ltc_eur', 'ltc_eur',],
            ['nmc_usd', 'nmc_usd',],
            ['nvc_usd', 'nvc_usd',],
            ['ppc_usd', 'ppc_usd',],
            ['eur_rur', 'eur_rur',],
            ['dsh_btc', 'dsh_btc',],
            ['dsh_usd', 'dsh_usd',],
            ['eth_btc', 'eth_btc',],
            ['eth_usd', 'eth_usd',],
            ['eth_eur', 'eth_eur',],
            ['eth_ltc', 'eth_ltc',],
            ['eth_rur', 'eth_rur',],
            ['dsh_rur', 'dsh_rur',],
            ['dsh_eur', 'dsh_eur',],
            ['dsh_ltc', 'dsh_ltc',],
            ['dsh_eth', 'dsh_eth',],
            ['bch_usd', 'bch_usd',],
            ['bch_btc', 'bch_btc',],
            ['bch_rur', 'bch_rur',],
            ['bch_eur', 'bch_eur',],
            ['bch_ltc', 'bch_ltc',],
            ['bch_eth', 'bch_eth',],
            ['bch_dsh', 'bch_dsh',],
            ['zec_btc', 'zec_btc',],
            ['zec_usd', 'zec_usd',],
            ['usdet_usd', 'usdet_usd',],
            ['ruret_rur', 'ruret_rur',],
            ['euret_eur', 'euret_eur',],
            ['btcet_btc', 'btcet_btc',],
            ['ltcet_ltc', 'ltcet_ltc',],
            ['ethet_eth', 'ethet_eth',],
            ['nmcet_nmc', 'nmcet_nmc',],
            ['nvcet_nvc', 'nvcet_nvc',],
            ['ppcet_ppc', 'ppcet_ppc',],
            ['dshet_dsh', 'dshet_dsh',],
            ['bchet_bch', 'bchet_bch',],
            ['dsh_zec', 'dsh_zec',],
            ['eth_zec', 'eth_zec',],
            ['bch_zec', 'bch_zec',],
            ['zec_ltc', 'zec_ltc',],
            ['usdt_usd', 'usdt_usd',],
            ['btc_usdt', 'btc_usdt',],
            ['xmr_usd', 'xmr_usd',],
            ['xmr_btc', 'xmr_btc',],
            ['xmr_eth', 'xmr_eth',],
            ['xmr_rur', 'xmr_rur',],
        ];
    }
}
