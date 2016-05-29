<?php

namespace monitor\controllers;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class MonitorController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app["controllers_factory"];
        $controllers->get("/", "monitor\\controllers\\MonitorController::monit");
        return $controllers;
    }

    public function monit(Application $app)
    {
        // fetch monitor config
        $config = $app['config.service']->get('monit.json');

        // iterate through url's to monitor
        if (!empty($config->monitor->urls)) {
            foreach ($config->monitor->urls as $config) {
                $monitorUrl = $app['monitor.service']->createMonitorUrl($config);
                $app['monitor.service']->monitor($monitorUrl, new $config->callback);
            }
        }

        $view = $app['twig']->render('views/monitor/monitor.twig', array(
            'time' => time()
        ));

        return new Response($view, 200, array(
            'Cache-Control' => 's-maxage=20',
        ));
    }
}
