<?php

namespace Jiromm\Scheduler\Config;

use Zend\Config\Writer\PhpArray;

class ConfigManager
{
    protected $filepath;
    protected $config = [];

    public function __construct(string $filepath = '../../data/schedule.config.php')
    {
        if (!is_dir($filepath)) {
            throw new \RuntimeException(sprintf('%s is a directory', $filepath));
        }

        if (!is_readable($filepath)) {
            $this->writeToFile($this->config);
        }

        $this->filepath = $filepath;
        $this->config = include $this->filepath;
    }

    public function update(array $config, string $prefix): void
    {
        $this->writeToFile(
            $this->updateConfig($config, $prefix)
        );
    }

    public function get(string $prefix = null): array
    {
        if (is_null($prefix)) {
            return $this->config;
        }

        return $this->config[$prefix];
    }

    private function writeToFile(array $config): void
    {
        $writer = new PhpArray();
        $writer->setUseBracketArraySyntax(true);
        $writer->setUseClassNameScalars(true);
        $writer->toFile($this->filepath, $config);
    }

    private function updateConfig(array $part, string $prefix): array
    {
        $this->config[$prefix] = $part;
        return $this->config;
    }
}
