<?php

namespace config\library\filereaders;

use config\exception\FileReadException;

class JsonReader implements FileReaderInterface
{

    public function read($file)
    {
        // file present?
        if (!is_file($file)) {
            throw new FileReadException('No such file: ' . $file);
        }

        $json = json_decode(file_get_contents($file), false);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw new FileReadException('Invalid json');
        }

        return $json;
    }

    public function open($file)
    {
        // TODO: Implement open() method.
    }

    public function close($file)
    {
        // TODO: Implement close() method.
    }
}
