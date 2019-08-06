<?php declare(strict_types=1);

namespace AlecRabbit\TWRBCore;

use AlecRabbit\TWRBCore\DataStruct\Trade;
use function AlecRabbit\array_unset_first;
use function AlecRabbit\base_timestamp;
use function AlecRabbit\Helpers\bounds;
use const AlecRabbit\TWRBCore\Constants\CLOSE;
use const AlecRabbit\TWRBCore\Constants\HIGH;
use const AlecRabbit\TWRBCore\Constants\LOW;
use const AlecRabbit\TWRBCore\Constants\OPEN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_01MIN;
use const AlecRabbit\TWRBCore\Constants\RESOLUTION_ALIASES;
use const AlecRabbit\TWRBCore\Constants\RESOLUTIONS;
use const AlecRabbit\TWRBCore\Constants\TIMESTAMP;
use const AlecRabbit\TWRBCore\Constants\VOLUME;

class DataOHLCV
{
    protected const DEFAULT_PERIOD_MULTIPLIER = 3;
    protected const MAX_PERIOD_MULTIPLIER = 100;
    protected const DEFAULT_SIZE = 500;
    protected const MAX_SIZE = 1440;
    protected const MIN_SIZE = 10;

    /** @var array */
    protected $current = [];
    /** @var array */
    protected $timestamps = [];
    /** @var array */
    protected $opens = [];
    /** @var array */
    protected $highs = [];
    /** @var array */
    protected $lows = [];
    /** @var array */
    protected $closes = [];
    /** @var array */
    protected $volumes = [];
    /** @var array */
    protected $proxies = [];
    /** @var int */
    private $size;
    /** @var int */
    private $coefficient;
    /** @var string */
    private $pair;

    /**
     * DataOHLCV constructor.
     * @param string $pair
     * @param int|null $size
     * @param int|null $coefficient
     */
    public function __construct(
        string $pair,
        ?int $size = null,
        ?int $coefficient = null
    ) {
        $this->size =
            (int)bounds(
                $size ?? static::DEFAULT_SIZE,
                static::MIN_SIZE,
                static::MAX_SIZE
            );
        $this->pair = $pair;
        $this->coefficient = $coefficient ?? 1;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    public function hasPeriods(int $resolution, int $periods, int $multiplier = null): bool
    {
        $multiplier =
            (int)bounds($multiplier ?? static::DEFAULT_PERIOD_MULTIPLIER, 1, static::MAX_PERIOD_MULTIPLIER);

        return
            isset($this->timestamps[$resolution])
            && (\count($this->timestamps[$resolution]) >= ($periods * $multiplier));
    }

    public function addTrade(Trade $trade): void
    {
        $this->addOHLCV(
            $trade->timestamp,
            $trade->price,
            $trade->price,
            $trade->price,
            $trade->price,
            $trade->amount,
            $trade->side
        );
    }

    public function addOHLCV(
        int $timestamp,
        float $open,
        float $high,
        float $low,
        float $close,
        float $volume,
        int $side,
        int $resolution = RESOLUTION_01MIN
    ): void {
        $ts = base_timestamp($timestamp, $resolution);
        if (isset($this->current[$resolution])) {
            if ($ts > $this->current[$resolution][TIMESTAMP]) {
                $this->updateLast($resolution);
                $this->setCurrent($resolution, $ts, $open, $high, $low, $close, $volume);
            } elseif ($ts === $this->current[$resolution][TIMESTAMP]) {
                $this->updateCurrent($resolution, $high, $low, $close, $volume);
            } elseif ($ts < $this->current[$resolution][TIMESTAMP]) {
                throw new \RuntimeException(
                    'Incoming data are in unsorted order. Current timestamp is greater then incoming data\'s.' .
                    ' (' . $ts . ' < ' . $this->current[$resolution][TIMESTAMP] . ')'
                );
            }
            $this->trim($resolution);
        } else {
            $this->setCurrent($resolution, $ts, $open, $high, $low, $close, $volume);
        }

        if ($nextResolution = $this->nextResolution($resolution)) {
            $this->addOHLCV($timestamp, $open, $high, $low, $close, $volume, $side, $nextResolution);
//            dump('here');
        }
    }

    private function updateLast(int $resolution): void
    {
        $this->timestamps[$resolution][] = $this->current[$resolution][TIMESTAMP];
        $this->opens[$resolution][] = $this->current[$resolution][OPEN];
        $this->highs[$resolution][] = $this->current[$resolution][HIGH];
        $this->lows[$resolution][] = $this->current[$resolution][LOW];
        $this->closes[$resolution][] = $this->current[$resolution][CLOSE];
        $this->volumes[$resolution][] = $this->current[$resolution][VOLUME];
    }

    private function setCurrent(
        int $resolution,
        int $timestamp,
        float $open,
        float $high,
        float $low,
        float $close,
        float $volume
    ): void {
        $this->current[$resolution][TIMESTAMP] = $timestamp;
        $this->current[$resolution][OPEN] = $open;
        $this->current[$resolution][HIGH] = $high;
        $this->current[$resolution][LOW] = $low;
        $this->current[$resolution][CLOSE] = $close;
        $this->current[$resolution][VOLUME] = $volume;
    }

    private function updateCurrent(
        int $resolution,
        float $high,
        float $low,
        float $close,
        float $volume
    ): void {
        if ($high > $this->current[$resolution][HIGH]) {
            $this->current[$resolution][HIGH] = $high;
        }
        if ($low < $this->current[$resolution][LOW]) {
            $this->current[$resolution][LOW] = $low;
        }

        $this->current[$resolution][CLOSE] = $close;
        $this->current[$resolution][VOLUME] += $volume;
//        $this->current[$resolution][VOLUME] =
//            (float)BC::add((string)$this->current[$resolution][VOLUME], (string)$volume, NORMAL_SCALE);
    }

    private function trim(int $resolution): void
    {
        if (isset($this->timestamps[$resolution]) && (\count($this->timestamps[$resolution]) > $this->size)) {
            array_unset_first($this->timestamps[$resolution]);
            array_unset_first($this->opens[$resolution]);
            array_unset_first($this->highs[$resolution]);
            array_unset_first($this->lows[$resolution]);
            array_unset_first($this->closes[$resolution]);
            array_unset_first($this->volumes[$resolution]);
        }
    }

    private function nextResolution(int $resolution): ?int
    {
        $key = array_search($resolution, RESOLUTIONS, true);
        if ($key !== false && array_key_exists(++$key, RESOLUTIONS)) {
            return RESOLUTIONS[$key];
        }
        return null;
    }

    /**
     * @param int $resolution
     * @return array
     */
    public function getTimestamps(int $resolution): array
    {
        return
            $this->timestamps[$resolution] ?? [];
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return array
     */
    public function getOpens(int $resolution, bool $useCoefficient = false): array
    {
        return
            $this->mulArr(
                $this->opens[$resolution] ?? [],
                $useCoefficient
            );
    }

    private function mulArr(array $values, bool $useCoefficient): array
    {
        if ($useCoefficient && $this->coefficient !== 1) {
            $values = array_map(
            /**
             * @param float|int $v
             * @return float|int
             */ function ($v) {
                return
                    $v * $this->coefficient;
            },
                $values
            );
        }

        return $values;
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return array
     */
    public function getHighs(int $resolution, bool $useCoefficient = false): array
    {
        return
            $this->mulArr(
                $this->highs[$resolution] ?? [],
                $useCoefficient
            );
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return null|float
     */
    public function getLastHigh(int $resolution, bool $useCoefficient = false): ?float
    {
        return
            $this->lastElement($this->highs[$resolution] ?? [], $useCoefficient);
    }

    /**
     * @param array $element
     * @param bool $useCoefficient
     * @return null|float
     */
    private function lastElement(array $element, bool $useCoefficient = false): ?float
    {
        if (false !== $lastElement = end($element)) {
            return
                $this->mul(
                    $lastElement,
                    $useCoefficient
                );
        }
        return null;
    }

    private function mul(float $value, bool $useCoefficient): float
    {
        if ($useCoefficient && $this->coefficient !== 1) {
            $value *= $this->coefficient;
        }
        return $value;
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return array
     */
    public function getLows(int $resolution, bool $useCoefficient = false): array
    {
        return
            $this->mulArr(
                $this->lows[$resolution] ?? [],
                $useCoefficient
            );
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return null|float
     */
    public function getLastLow(int $resolution, bool $useCoefficient = false): ?float
    {
        return
            $this->lastElement($this->lows[$resolution] ?? [], $useCoefficient);
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return array
     */
    public function getCloses(int $resolution, bool $useCoefficient = false): array
    {
        return
            $this->mulArr(
                $this->closes[$resolution] ?? [],
                $useCoefficient
            );
    }

    /**
     * @param int $resolution
     * @param bool $useCoefficient
     * @return null|float
     */
    public function getLastClose(int $resolution, bool $useCoefficient = false): ?float
    {
        return
            $this->lastElement($this->closes[$resolution] ?? [], $useCoefficient);
    }

    /**
     * @param int $resolution
     * @return array
     */
    public function getVolumes(int $resolution): array
    {
        return
            $this->volumes[$resolution] ?? [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function dump(): void
    {
        if (\defined('APP_DEBUG') && APP_DEBUG && !empty($this->current)) {
            $result = [];
            $pair = $this->getPair();
            foreach (RESOLUTIONS as $resolution) {
                $count = \count($this->timestamps[$resolution] ?? []) + 1;
                $result[] =
                    sprintf(
                        '%s|%s [%s] %s %s %.6f %.6f %.6f %.6f %.8f',
                        date('H:i:s', $this->current[$resolution][TIMESTAMP]),
                        $this->current[$resolution][TIMESTAMP],
                        RESOLUTION_ALIASES[$resolution],
                        $count,
                        $pair,
                        $this->current[$resolution][OPEN],
                        $this->current[$resolution][HIGH],
                        $this->current[$resolution][LOW],
                        $this->current[$resolution][CLOSE],
                        $this->current[$resolution][VOLUME]
                    );
            }
            dump($result);
        }
    }

    /**
     * @return string
     */
    public function getPair(): string
    {
        return $this->pair;
    }
}
