<?php declare(strict_types=1);

namespace AlecRabbit\TWRBCore\Constants;

// @codeCoverageIgnoreStart
// String constants
define(__NAMESPACE__ . '\VOLUME', 'volume');
define(__NAMESPACE__ . '\CLOSE', 'close');
define(__NAMESPACE__ . '\LOW', 'low');
define(__NAMESPACE__ . '\HIGH', 'high');
define(__NAMESPACE__ . '\OPEN', 'open');
define(__NAMESPACE__ . '\TIMESTAMP', 'timestamp');

define(__NAMESPACE__ . '\STR_VP', 'vp_');
define(__NAMESPACE__ . '\STR_P_SUM', 'p_sum_');
define(__NAMESPACE__ . '\STR_VWAP', 'vwap_');
define(__NAMESPACE__ . '\STR_AVG_PRICE', 'avg_price_');

define(__NAMESPACE__ . '\STR_EVENTS', 'events');
define(__NAMESPACE__ . '\STR_VOLUMES', 'volumes');
define(__NAMESPACE__ . '\STR_AVERAGES', 'averages');

define(__NAMESPACE__ . '\STR_TOTAL', 'total');
define(__NAMESPACE__ . '\STR_SELL', 'sell');
define(__NAMESPACE__ . '\STR_BUY', 'buy');

define(__NAMESPACE__ . '\STR_P_SUM_TOTAL', STR_P_SUM . STR_TOTAL);
define(__NAMESPACE__ . '\STR_P_SUM_SELL', STR_P_SUM . STR_SELL);
define(__NAMESPACE__ . '\STR_P_SUM_BUY', STR_P_SUM . STR_BUY);

define(__NAMESPACE__ . '\STR_VWAP_TOTAL', STR_VWAP . STR_TOTAL);
define(__NAMESPACE__ . '\STR_VWAP_SELL', STR_VWAP . STR_SELL);
define(__NAMESPACE__ . '\STR_VWAP_BUY', STR_VWAP . STR_BUY);

define(__NAMESPACE__ . '\STR_AVG_PRICE_TOTAL', STR_AVG_PRICE . STR_TOTAL);
define(__NAMESPACE__ . '\STR_AVG_PRICE_SELL', STR_AVG_PRICE . STR_SELL);
define(__NAMESPACE__ . '\STR_AVG_PRICE_BUY', STR_AVG_PRICE . STR_BUY);

define(__NAMESPACE__ . '\STR_VP_TOTAL', STR_VP . STR_TOTAL);
define(__NAMESPACE__ . '\STR_VP_SELL', STR_VP . STR_SELL);
define(__NAMESPACE__ . '\STR_VP_BUY', STR_VP . STR_BUY);

// Trade constants
define(__NAMESPACE__ . '\T_SELL', 100);
define(__NAMESPACE__ . '\T_BUY', -100);

define(__NAMESPACE__ . '\T_ASK', T_SELL);
define(__NAMESPACE__ . '\T_BID', T_BUY);
define(
    __NAMESPACE__ . '\T_ALIASES',
    [
        'sell' => T_SELL,
        'buy' => T_BUY,
        'ask' => T_ASK,
        'bid' => T_BID,
    ]
);

// Time constants
define(__NAMESPACE__ . '\ONE_SECOND', 1);

define(__NAMESPACE__ . '\SECONDS_IN_01MIN', 60);
define(__NAMESPACE__ . '\SECONDS_IN_03MIN', 180);
define(__NAMESPACE__ . '\SECONDS_IN_05MIN', 300);
define(__NAMESPACE__ . '\SECONDS_IN_15MIN', 900);
define(__NAMESPACE__ . '\SECONDS_IN_30MIN', 1800);
define(__NAMESPACE__ . '\SECONDS_IN_45MIN', 2700);
define(__NAMESPACE__ . '\SECONDS_IN_01HOUR', 3600);
define(__NAMESPACE__ . '\SECONDS_IN_02HOUR', 7200);
define(__NAMESPACE__ . '\SECONDS_IN_03HOUR', 10800);
define(__NAMESPACE__ . '\SECONDS_IN_04HOUR', 14400);
define(__NAMESPACE__ . '\SECONDS_IN_01DAY', 86400);

define(__NAMESPACE__ . '\RESOLUTION_01MIN', SECONDS_IN_01MIN);
define(__NAMESPACE__ . '\RESOLUTION_03MIN', SECONDS_IN_03MIN);
define(__NAMESPACE__ . '\RESOLUTION_05MIN', SECONDS_IN_05MIN);
define(__NAMESPACE__ . '\RESOLUTION_15MIN', SECONDS_IN_15MIN);
define(__NAMESPACE__ . '\RESOLUTION_30MIN', SECONDS_IN_30MIN);
define(__NAMESPACE__ . '\RESOLUTION_45MIN', SECONDS_IN_45MIN);
define(__NAMESPACE__ . '\RESOLUTION_01HOUR', SECONDS_IN_01HOUR);
define(__NAMESPACE__ . '\RESOLUTION_02HOUR', SECONDS_IN_02HOUR);
define(__NAMESPACE__ . '\RESOLUTION_03HOUR', SECONDS_IN_03HOUR);
define(__NAMESPACE__ . '\RESOLUTION_04HOUR', SECONDS_IN_04HOUR);
define(__NAMESPACE__ . '\RESOLUTION_01DAY', SECONDS_IN_01DAY);

define(__NAMESPACE__ . '\P_01MIN', SECONDS_IN_01MIN);
define(__NAMESPACE__ . '\P_03MIN', SECONDS_IN_03MIN);
define(__NAMESPACE__ . '\P_05MIN', SECONDS_IN_05MIN);
define(__NAMESPACE__ . '\P_15MIN', SECONDS_IN_15MIN);
define(__NAMESPACE__ . '\P_30MIN', SECONDS_IN_30MIN);
define(__NAMESPACE__ . '\P_45MIN', SECONDS_IN_45MIN);
define(__NAMESPACE__ . '\P_01HOUR', SECONDS_IN_01HOUR);
define(__NAMESPACE__ . '\P_02HOUR', SECONDS_IN_02HOUR);
define(__NAMESPACE__ . '\P_03HOUR', SECONDS_IN_03HOUR);
define(__NAMESPACE__ . '\P_04HOUR', SECONDS_IN_04HOUR);
define(__NAMESPACE__ . '\P_01DAY', SECONDS_IN_01DAY);

define(
    __NAMESPACE__ . '\RESOLUTIONS',
    [
        RESOLUTION_01MIN,
        RESOLUTION_03MIN,
        RESOLUTION_05MIN,
        RESOLUTION_15MIN,
        RESOLUTION_30MIN,
        RESOLUTION_45MIN,
        RESOLUTION_01HOUR,
        RESOLUTION_02HOUR,
        RESOLUTION_03HOUR,
        RESOLUTION_04HOUR,
        RESOLUTION_01DAY,
    ]
);

define(
    __NAMESPACE__ . '\PERIODS',
    [ // Period => GroupBy
        P_01MIN => ONE_SECOND,
        P_03MIN => P_01MIN,
        P_05MIN => P_01MIN,
        P_15MIN => P_05MIN,
        P_30MIN => P_05MIN,
        P_45MIN => P_05MIN,
        P_01HOUR => P_15MIN,
        P_02HOUR => P_15MIN,
        P_03HOUR => P_15MIN,
        P_04HOUR => P_15MIN,
        P_01DAY => P_01HOUR,
    ]
);

define(
    __NAMESPACE__ . '\RESOLUTION_ALIASES',
    [
        RESOLUTION_01MIN => '01m',
        RESOLUTION_03MIN => '03m',
        RESOLUTION_05MIN => '05m',
        RESOLUTION_15MIN => '15m',
        RESOLUTION_30MIN => '30m',
        RESOLUTION_45MIN => '45m',
        RESOLUTION_01HOUR => '01h',
        RESOLUTION_02HOUR => '02h',
        RESOLUTION_03HOUR => '03h',
        RESOLUTION_04HOUR => '04h',
        RESOLUTION_01DAY => '01d',
    ]
);

// Math constants
define(__NAMESPACE__ . '\NORMAL_SCALE', 9);
define(__NAMESPACE__ . '\EXTENDED_SCALE', 14);
// @codeCoverageIgnoreEnd
