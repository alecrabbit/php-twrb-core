<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once 'debug.php'; // Set debug constants

use AlecRabbit\Spinner\SnakeSpinner;
use NunoMaduro\Collision\Provider;
use React\EventLoop\Factory;
use Rx\Scheduler;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;

(new Provider)->register(); // Error handling

$cloner = new VarCloner();
$fallbackDumper = \in_array(\PHP_SAPI, ['cli', 'phpdbg']) ? new CliDumper() : new HtmlDumper();
$dumper = new ServerDumper('tcp://127.0.0.1:9912', $fallbackDumper, [
    'cli' => new CliContextProvider(),
    'source' => new SourceContextProvider(),
]);

VarDumper::setHandler(
    static function ($var) use ($cloner, $dumper) {
        $dumper->dump($cloner->cloneVar($var));
    }
);

$loop = Factory::create();
try {
    Scheduler::setDefaultFactory(static function () use ($loop) {
        return new Scheduler\EventLoopScheduler($loop);
    });
} catch (Exception $e) {
    echo sprintf(
        'Error: [%s] %s',
        get_class($e),
        $e->getMessage()
    );
    echo PHP_EOL;
    exit(1);
}

function asString($value) {
    if (is_array($value)) {
        return json_encode($value);
    }
    if (is_bool($value)) {
        return (string)(integer)$value;
    }
    return (string) $value;
}

$createStdoutObserver =
    static function ($prefix = '') {
        return new Rx\Observer\CallbackObserver(
            static function ($value) use ($prefix) {
                echo $prefix . 'Next value: ' . asString($value) . "\n";
            },
            static function (\Throwable $error) use ($prefix) {
                echo $prefix . 'Exception: ' . $error->getMessage() . "\n";
            },
            static function () use ($prefix) {
                echo $prefix . 'Complete!' . PHP_EOL;
            }
        );
    };

$stdoutObserver = $createStdoutObserver();

$spinner = new SnakeSpinner();

$loop->addPeriodicTimer(
    $spinner->interval(),
    static function () use ($spinner) {
        $spinner->spin();
    });

register_shutdown_function(static function () use ($loop, $spinner) {
    $loop->run();
    $spinner->end();
});

$spinner->begin();