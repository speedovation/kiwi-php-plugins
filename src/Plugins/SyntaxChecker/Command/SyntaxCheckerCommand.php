<?php

namespace KiWi\Plugins\SyntaxChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use KiWi\DependencyInjection\Application;

//"classmap": ["syntaxchecker/command", "syntaxchecker"],
//"speedovation/php-token-reflection" : "~1.4.0",

class SyntaxCheckerCommand extends Command
{
    /**
     * @var Application
     */
    protected $app;
    /**
     * It provides direct access to the whole easybook dependency injection container.
     *
     * @return Application The object that represents the dependency injection container
     */
    public function getApp()
    {
        return $this->app;
    }
    protected function initialize(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->app = $this->getApplication()->getApp();
        
        //print_r($this->getApplication()->getApp()['api']->hello() );
    }
    
    // php syntaxchecker/interface.php 
    // checkSyntax 
    // '{\"selected_text\":\"\",\"file_path\":\"/home/yash/Projects/kiwi/Build/Debug/resources/flavours/php/test/test2.php\"}' 
    //9040

    protected function configure()
    {
        $this
            ->setName('checkSyntax')
            ->setDescription('Check Syntax of PHP file')
           
            ->addArgument(
                'data',
                InputArgument::REQUIRED,
                'Available methods data'
            )
            
             ->addArgument(
                'port',
                InputArgument::REQUIRED,
                'port'
            )
            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        //Testing
        echo "\n\n"; //$this->app['api']->hello();

        //$name = $input->getArgument('name');
        $data = $input->getArgument('data');
        $port = $input->getArgument('port');
        
        $text = "Data: ".$data . " Port: ".$port;
        
        //$this->app['api']->decode($data);
        
        $this->app['SyntaxChecker']->checkSyntax($data);
        
        /*if ($name) 
        {
            $text = 'Hello '.$name;
        } 
        else 
        {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) 
        {
            $text = strtoupper($text);
        }*/

        $output->writeln($text);
    }
}
