<?php

namespace config\service;

use config\library\filereaders\FileReaderInterface;

class ConfigService
{
    private $reader;

    private $configPath;

    public function __construct(FileReaderInterface $reader, $configPath)
    {
        $this->reader = $reader;
        $this->configPath = $configPath;
    }

    public function get($configFile)
    {
        return $this->reader->read($this->configPath . '/' . $configFile);
    }
}
