<?php

namespace Jiromm\Scheduler\Task\Interfaces;

interface RecurringInterface
{
    public function interval(): int;
}
