<?php

namespace Jiromm\Scheduler\Task\Interfaces;

interface DelayedInterface
{
    public function delay(): int;
}
