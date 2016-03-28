<?php

namespace KiWi\Util\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class AboutCommand extends BaseCommand
{
    private $signature;
    
    public function __construct($signature)
    {
        parent::__construct();
        $this->signature = $signature;
    }
    
    protected function configure()
    {
        $this
            ->setName('about')
            ->setDescription('Displays the easybook usage help')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf(file_get_contents(__DIR__.'/Resources/AboutCommandHelp.txt'), $this->signature));
    }
}
