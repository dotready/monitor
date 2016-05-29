<?php

namespace monitor\provider;

use monitor\service\MonitorService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MonitorServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $config = $app['config.service']->get('monit.json');
        $app['monitor.service'] = new MonitorService(
            $app['mail.service'], $config->panicEmailAddress,
            $app['session']
        );
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}