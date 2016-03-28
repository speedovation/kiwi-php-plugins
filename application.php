#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use KiWi\Plugins\Syntaxchecker\Command\GreetCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new GreetCommand());
$application->run();
