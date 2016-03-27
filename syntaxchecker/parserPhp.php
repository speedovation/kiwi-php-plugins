<?php

use PhpParser\Error;
use PhpParser\ParserFactory;


function checkSyntaxPhp($code)
{

    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    
    try 
    {
        $stmts = $parser->parse($code);
        // $stmts is an array of statement nodes
        return "";
    } 
    catch (Error $e) 
    {
        return "Parse Error: ". $e->getMessage();
    }

}
