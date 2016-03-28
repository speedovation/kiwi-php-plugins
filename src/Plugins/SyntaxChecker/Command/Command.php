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

class GreetCommand extends Command
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
    
    
    protected function configure()
    {
        
        
        $this
            ->setName('demo:greet')
            ->setDescription('Greet someone')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
               'yell',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        //Testing
        echo "\n\n".$this->app['api']->hello();

        $name = $input->getArgument('name');
        if ($name) 
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
        }

        $output->writeln($text);
    }
}
