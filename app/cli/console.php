<?php

require __DIR__ . "/..//bootstrap/bootstrap.php";

$request = $argv[1];

$subRequest = \Symfony\Component\HttpFoundation\Request::create($request);
$response = $app->handle($subRequest, \Symfony\Component\HttpKernel\HttpKernelInterface::MASTER_REQUEST, false);
