<?php

namespace KiWi\Plugins\SyntaxChecker\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SyntaxCheckerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['SyntaxChecker'] = function () use ($app) 
		{
            $api = new \KiWi\Plugins\SyntaxChecker\Logic\Checker($app);
            
            return $api;
        };
    }
}
