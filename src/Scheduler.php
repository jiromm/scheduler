<?php

namespace Jiromm\Scheduler;

use Jiromm\Scheduler\Config\ConfigManager;
use Jiromm\Scheduler\Task\Interfaces\DelayedInterface;
use Jiromm\Scheduler\Task\Interfaces\PeriodicInterface;
use Jiromm\Scheduler\Task\Interfaces\RecurringInterface;
use Jiromm\Scheduler\Task\Interfaces\TaskInterface;
use React\EventLoop\LoopInterface;

class Scheduler
{
    const SMALLEST_INTERVAL = 1;

    protected $loop;
    protected $configManager;

    public function __construct(
        ConfigManager $configManager,
        LoopInterface $loop
    )
    {
        $this->loop = $loop;
        $this->configManager = $configManager;
    }

    public function add(TaskInterface $task)
    {
        $body = $task($this);

        if ($task instanceof PeriodicInterface) {
            $this->loop->addPeriodicTimer(self::SMALLEST_INTERVAL, function () use ($body, $task) {
                $config = $this->configManager->get($task->prefix());

                $interval = $task->interval();
                $lastExecutionTime = $config['last'] ?? date('Y-m-d H:i:s', strtotime('humans first appearance'));
                $nextExecutionTime = date('Y-m-d H:i:s', strtotime($lastExecutionTime . ' + ' . $interval . ' seconds'));
                $now = date('Y-m-d H:i:s');

                if ($nextExecutionTime <= $now) {
                    $this->loop->futureTick($body);

                    $config['last'] = $now;
                    $this->configManager->update($config, $task->prefix());
                }
            });
        } elseif ($task instanceof RecurringInterface) {
            $this->loop->addPeriodicTimer($task->interval(), $body);
        } elseif ($task instanceof DelayedInterface) {
            $this->loop->addTimer($task->delay(), $body);
        } else {
            $this->loop->futureTick($body);
        }
    }

    public function run()
    {
        $this->loop->run();
    }
}
