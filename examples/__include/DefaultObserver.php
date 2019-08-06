<?php declare(strict_types=1);

namespace AlecRabbit\Rx;
use Rx\Observer\CallbackObserver;
use function AlecRabbit\typeOf;

class DefaultObserver extends CallbackObserver
{
    public function __construct(callable $onNext = null, callable $onError = null, callable $onCompleted = null)
    {
        $onError =
            $onError ?? static function (\Throwable $error) {
                echo '[' . typeOf($error) . '] ' . $error->getMessage() . ' code: ' . $error->getCode() . PHP_EOL;
            };
        $onCompleted =
            $onCompleted ?? static function () {
                echo 'Stopped trying' . PHP_EOL;
            };

        parent::__construct($onNext, $onError, $onCompleted);
    }
}