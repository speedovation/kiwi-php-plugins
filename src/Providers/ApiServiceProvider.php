<?php

namespace KiWi\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ApiServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['api'] = function () use ($app) 
		{
            $api = new \KiWi\Util\Api($app);
            
            return $api;
        };
    }
}
