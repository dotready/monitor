<?php

namespace monitor\library\monitorurl;

interface MonitorUrlInterface
{
    public function setHost($url);

    public function setPath($path);

    public function setSsl($ssl);

    public function poll();

    public function getStatus();
}