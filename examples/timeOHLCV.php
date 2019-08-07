<?php declare(strict_types=1);

use AlecRabbit\Accessories\MemoryUsage;
use AlecRabbit\Rx\DefaultObserver;
use AlecRabbit\TWRBCore\DataOHLCV;
use AlecRabbit\TWRBCore\DataStruct\Trade;

require_once __DIR__ . '/../examples/__include/bootstrap.php';
require_once __DIR__ . '/../examples/__include/DefaultObserver.php';


$symbol = 'BTCUSDt';
$ohlcv = new DataOHLCV($symbol);

$url =
    sprintf(
        'wss://stream.binance.com:9443/ws/%s@trade',
        strtolower($symbol)
    );

$client = new \Rx\Websocket\Client($url, false, [], $loop);

$messagesObserver = new DefaultObserver(
    static function ($decoded) use ($ohlcv, $symbol) {
        if (null !== $decoded) {
            $tradeTime = (int)($decoded->T / 1000);
            $milliseconds = sprintf('%+d', $decoded->E - $decoded->T);
            $microtime = microtime(true) * 1000;
            $str = sprintf(
                '%s.%03d %s %s %s %s %s %s %+.1fms %+.1fms',
                date('H:i:s', $tradeTime),
                $decoded->T - $tradeTime * 1000,
                $decoded->t ?? $decoded->a,
                $decoded->p,
                $decoded->q,
                $decoded->m ? 'S' : 'B',
                $microtime,
                $decoded->T,
                $microtime - $decoded->T,
                $milliseconds
            );
            $start = hrtime(true);
            $trade = new Trade((int)$decoded->m, $symbol, (float)$decoded->p, (float)$decoded->q, $tradeTime);
            $ohlcv->addTrade($trade);
            $stop = hrtime(true);
            // unset($trade);
            echo $str . ' ' . sprintf('%.1fμs', ($stop - $start) / 1000) . PHP_EOL;
        }
    }
);

$webSocketObserver = new DefaultObserver(
    static function (\Rx\Websocket\MessageSubject $ms) use ($messagesObserver) {
        $ms
            ->map(
                static function ($message) {
                    return json_decode($message, false);
                })
            ->subscribe($messagesObserver);
    }
);

$client->subscribe(
    $webSocketObserver
);
$loop->addPeriodicTimer(
    10,
    static function () {
        dump((string)MemoryUsage::getReport());
    });

$loop->addPeriodicTimer(
    10,
    static function () use ($ohlcv) {
        $ohlcv->dump();
//        dump(1);
    });


//  Trade                                                         AggTrade

//{                                                             {
//  "e": "trade",     // Event type                               "e": "aggTrade",  // Event type
//  "E": 123456789,   // Event time                               "E": 123456789,   // Event time
//  "s": "BNBBTC",    // Symbol                                   "s": "BNBBTC",    // Symbol
//  "t": 12345,       // Trade ID                                 "a": 12345,       // Aggregate trade ID
//  "p": "0.001",     // Price                                    "p": "0.001",     // Price
//  "q": "100",       // Quantity                                 "q": "100",       // Quantity
//  "b": 88,          // Buyer order ID                           "f": 100,         // First trade ID
//  "a": 50,          // Seller order ID                          "l": 105,         // Last trade ID
//  "T": 123456785,   // Trade time                               "T": 123456785,   // Trade time
//  "m": true,        // Is the buyer the market maker?           "m": true,        // Is the buyer the market maker?
//  "M": true         // Ignore                                   "M": true         // Ignore
//}                                                             }
