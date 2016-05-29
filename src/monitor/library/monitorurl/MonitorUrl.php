<?php

namespace monitor\library\monitorurl;

use monitor\exception\MonitorException;
use monitor\exception\MonitorUrlException;

class MonitorUrl implements MonitorUrlInterface
{
    private $host = '';

    private $port = 80;

    private $timeout = 20;

    private $transport = 'tcp';

    private $path = '';

    public function getStatus()
    {
        $result = $this->poll();

        if (strpos($result, '404') !== false) {
            throw new MonitorUrlException('Encountered 404 when requesting: ' . $this->host . '' . $this->path);
        }

        return $result;
    }

    public function setHost($host)
    {
        if (empty($host)) {
            throw new MonitorUrlException('Host can not be empty');
        }

        $this->host = $host;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setSsl($ssl)
    {
        if ((bool) $ssl === true) {
            $this->port = 443;
            $this->transport = 'ssl';
        }
    }

    public function poll()
    {
        set_error_handler(array($this, "warningHandler"), E_WARNING);

        $client = stream_socket_client(
            $this->transport . '://' . $this->host . ':' . $this->port,
            $errno, $errstr, $this->timeout
        );

        if ($client === false) {
            throw new MonitorException('Unable to reach host');
        }

        restore_error_handler();

        fwrite($client, "GET ".$this->path." HTTP/1.0\r\nAccept: */*\r\n\r\n");

        return stream_get_contents($client);
    }

    public function warningHandler($errno, $errstr) {
        // do something useful
    }
}
