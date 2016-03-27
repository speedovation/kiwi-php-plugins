<?php

require __DIR__."/../vendor/autoload.php";

//These lines will be used by IDE to call required functions
if(function_exists( $argv[1] ))
call_user_func($argv[1], $argv[2]);


// $json = $argv[2];
function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}


function return_result($method, $args)
{
    return '{ "method" : "'.$method.'", "params" : '.$args.'}';
}



function checkSyntax($json)
{
    //bool runkit_lint ( string $code )
    
    //runkit_lint();
    
    echo "\n\n";
    
    $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
    
    $request = $connection->sendRequest('text');
    
    if(!$request->isError())
    {
        print $request->result;
    }
    else
    {
        print 'Error '.$request->error.': '.$request->errorMessage;
        var_dump($request->errorData);
    }
    
    echo "\n\nDone: Checking Syntax of PHP File\n\n";
    
    
    
}

