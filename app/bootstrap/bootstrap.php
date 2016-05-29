<?php

use Symfony\Component\HttpFoundation\Response;

// do not supress any errors (it's dev)
ini_set('display_errors', 'on');
error_reporting(E_ALL);

// autoloading
require __DIR__ . "/../../vendor/autoload.php";

// new app
$app = new Silex\Application();

// twig setup
$twigParams = array(
    'twig.path' => __DIR__ . '/../../templates/',
);

$app->register(new Silex\Provider\TwigServiceProvider(), $twigParams);

// app config path
$app['configpath'] = __DIR__ . '/../../app/config';

// set error pages
$app->error(function ( \Exception $e, $code ) use ($app) {

    $view = $app['twig']->render('errors/'. (int) $code. '.twig', array(
        'exception' => $e,
        'code' => $code
    ));

    return new Response($view, 200, array(
        'Cache-Control' => 's-maxage=20',
    ));
});

$app->before(function ($request) {
    $request->getSession()->start();
});

// everybody likes sessions
$app->register(new Silex\Provider\SessionServiceProvider());

// register config service
$app->register( new \config\provider\ConfigServiceProvider());

// register mail service
$app->register( new \mail\provider\MailServiceProvider());

// register monitor service
$app->register( new \monitor\provider\MonitorServiceProvider());

// mount monitor domain
$app->mount("/", new \monitor\controllers\MonitorController());