#!/usr/bin/env php
<?php


include __DIR__ . '/../vendor/autoload.php';

use QafooLabs\Refactoring\Adapters\Symfony\CliApplication;

$application = new CliApplication();
$application->run();


/*
Refactorings
Extract Method   
php refactor.phar extract-method <file_path> <line-range> <new-method>
*/
function extractMethod()
{
    $try = '';
}


/*
Rename Local Variable
php refactor.phar rename-local-variable <file_path> <line> <old-name> <new-name>
*/
function renameLocalVariable()
{
    
}


/*
Convert Local to Instance Variable
php refactor.phar convert-local-to-instance-variable <file_path> <line> <variable>
*/
function convertLocalToInstanceVariable()
{
    
}


/*
Rename Class and Namespaces
php refactor.phar fix-class-names <dir>
*/
function renameClassAndNamespaces()
{
    
}


/*
Optimize use statements
php refactor.phar optimize-use <file_path>
*/
function optimizeUse()
{
    
}
