<?php

namespace Jiromm\Scheduler\Task\Interfaces;

use Jiromm\Scheduler\Scheduler;

interface TaskInterface
{
    public function __invoke(Scheduler $scheduler): callable;
    public function prefix(): string;
}
