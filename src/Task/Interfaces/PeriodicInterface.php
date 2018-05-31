<?php

namespace Jiromm\Scheduler\Task\Interfaces;

interface PeriodicInterface
{
    public function interval(): int;
}
