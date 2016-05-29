<?php

namespace config\provider;

use config\library\filereaders\JsonReader;
use config\service\ConfigService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }

    public function register(Application $app)
    {
        $app['config.service'] = new ConfigService(
            new JsonReader(),
            $app['configpath']
        );
    }
}
