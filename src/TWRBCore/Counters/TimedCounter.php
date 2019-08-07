<?php declare(strict_types=1);

namespace AlecRabbit\TWRBCore\Counters;

use function AlecRabbit\base_timestamp;
use AlecRabbit\Traits\Nameable;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use const AlecRabbit\TWRBCore\Constants\PERIODS;

class TimedCounter
{
    use Nameable;

    /** @var int */
    protected $lastTimestamp;

    /** @var array */
    protected $data = [];

    /** @var bool */
    protected $relativeMode = false;

    /** @var bool */
    protected $precisionMode = false;

    /** @var array */
    protected $periods;

    /**
     * BasicCounter constructor.
     * @param array|null $periods
     */
    public function __construct(?array $periods = null)
    {
        $this->setName(DEFAULT_NAME);
        $this->lastTimestamp = 0;
        $this->periods = $periods ?? PERIODS;
    }

    /**
     * Enables relative time mode
     * @return self
     */
    public function enableRelativeMode(): self
    {
        $this->relativeMode = true;
        return $this;
    }

    /**
     * @return self
     */
    public function enablePrecisionMode(): self
    {
        $this->precisionMode = true;
        return $this;
    }

    /**
     * @param int|null $time
     */
    public function add(?int $time = null): void
    {
        $baseTimes = $this->getBaseTimes($time);
        foreach ($baseTimes as $period => $timestamp) {
            $this->data[$period][$timestamp] =
                $this->data[$period][$timestamp] ?? 0;
            $this->data[$period][$timestamp]++;
            $this->trim($period);
        }
    }

    /**
     * @param int|null $timestamp
     * @return array
     */
    protected function getBaseTimes(?int $timestamp = null): array
    {
        $this->lastTimestamp = $time = $timestamp ?? time();
        $baseTimes = [];
        foreach ($this->periods as $period => $groupBy) {
            $baseTimes[$period] = base_timestamp($time, $groupBy);
        }
        return $baseTimes;
    }

    /**
     * @param int $period
     */
    protected function trim(int $period): void
    {
        if (null !== ($key = array_key_first($this->data[$period]))
            && ($key <= $this->getThreshold($period))) {
            unset($this->data[$period][$key]);
        }
    }

    /**
     * @param int $period
     * @return int
     */
    protected function getThreshold(int $period): int
    {
        return
            $this->getTime() - $period;
    }

    /**
     * @return int
     */
    protected function getTime(): int
    {
        return
            $this->relativeMode ? $this->lastTimestamp : time();
    }

    /**
     * @param bool|null $reset
     * @return array
     */
    public function getDataArray(?bool $reset = null): array
    {
        $r = [];
        foreach ($this->periods as $period => $groupBy) {
            if (0 < ($sum = array_sum($this->data[$period] ?? []))) {
                $r[$period] = $sum;
            }
        }
        if ($reset) {
            $this->reset();
        }
        return $r;
    }

    /**
     * Resets all data
     */
    protected function reset(): void
    {
        $this->data = [];
    }

    /**
     * @param bool|null $reset
     * @return object
     */
    public function getDataObject(?bool $reset = null): object
    {
        $r = new \stdClass();
        foreach ($this->periods as $period => $groupBy) {
            if (0 < ($sum = array_sum($this->data[$period] ?? []))) {
                $r->{$period} = $sum;
            }
        }
        if ($reset) {
            $this->reset();
        }
        return $r;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->data;
    }
}
