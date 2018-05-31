<?php

namespace Jiromm\Scheduler\Task;

use Jiromm\Scheduler\Scheduler;
use Jiromm\Scheduler\Task\Interfaces\PeriodicInterface;
use Jiromm\Scheduler\Task\Interfaces\TaskInterface;

class DummyTask implements TaskInterface, PeriodicInterface
{
    public function __invoke(Scheduler $scheduler): callable
    {
        return function () {
            echo 'Dummy task is running every 3 seconds' . PHP_EOL;
        };
    }

    public function interval(): int
    {
        return 1;
    }

    public function prefix(): string
    {
        return 'dummy';
    }
}
