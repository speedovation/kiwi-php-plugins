<?php

namespace KiWi\Console;

//use Symfony\Component\Console\Application;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

use KiWi\Plugins\SyntaxChecker\Command\SyntaxCheckerCommand;
use KiWi\Util\Command\AboutCommand;
use KiWi\DependencyInjection\Application;

//$application = new Application();
//$application->add(new GreetCommand());
//$application->run();

//define('PLUGINSPATH', realpath(__DIR__.'/../Plugins/') ); 

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
        //$this->add(new SyntaxCheckerCommand());
        
        $this->loadPlugins();
        
        $this->setDefaultCommand('about');
    }
    
    
    public function loadCommands($path)
    {
        
        //$providers = (array) require __DIR__.'/providers.php';
        //array_walk($providers, function($class, $i, $app) {
        //  class_exists($class) AND $app->register(new $class);
        //}, $app);
        
        $iterator = new \DirectoryIterator(PLUGINSPATH."/$path/Command/" );
        foreach ($iterator as $fileinfo) 
        {
            if ($fileinfo->isFile() )
            {
                //echo "\n\Command: ". $fileinfo->getFilename() . "\n\n";
                
                $class = $fileinfo->getBasename('.php');
                
                $class = "\KiWi\Plugins\\".$path."\\Command\\".$class;
                
                $this->add(new $class() ) ; 
            }   
        }
  
    }
        
    public function loadPlugins()
    {
         
        //
        $iterator = new \DirectoryIterator( PLUGINSPATH );
        foreach ($iterator as $fileinfo) 
        {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) 
            {
                //echo $fileinfo->getFilename() . "\n";
                
                
                //We are inside plgin dir
                $p = PLUGINSPATH."/".$fileinfo->getFilename()."/Command/";
                
                //echo "\n\nPP:". $p . PHP_EOL;
                
                if(!file_exists($p))
                {
                    echo "\nNo Command found in $p";
                    continue;
                }
                
                $this->loadCommands( $fileinfo->getFilename() );
                
                
                // recursion goes here.
            }
        }
    }
}
