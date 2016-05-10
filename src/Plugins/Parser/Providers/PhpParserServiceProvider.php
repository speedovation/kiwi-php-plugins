<?php

namespace KiWi\Plugins\Parser\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PhpParserServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['PhpParser'] = function () use ($app) 
		{
            $api = new \KiWi\Plugins\Parser\Logic\PhpParser($app);
            
            return $api;
        };
    }
}
