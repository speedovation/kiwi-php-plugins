<?php

namespace KiWi\Console;

//use Symfony\Component\Console\Application;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

use KiWi\Plugins\SyntaxChecker\Command\GreetCommand;
use KiWi\Util\Command\AboutCommand;
use KiWi\DependencyInjection\Application;

//$application = new Application();
//$application->add(new GreetCommand());
//$application->run();


class ConsoleApplication extends SymfonyConsoleApplication
{
    private $app;
    
    public function getApp()
    {
        return $this->app;
    }
    
    public function __construct(Application $app)
    {
        $this->app = $app;
        
        parent::__construct('KiWi', $this->app->getVersion());
        
        $this->add(new AboutCommand($this->app['app.signature']));
        $this->add(new GreetCommand());
        $this->setDefaultCommand('about');
    }
}
