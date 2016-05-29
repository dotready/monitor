<?php

namespace config\library\filereaders;

interface FileReaderInterface
{
    public function open($file);

    public function read($file);

    public function close($file);
}