<?php

require __DIR__."/../vendor/autoload.php";

include "parserPhp.php";

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
    
    $json = str_replace("'","",$json);
    $json = str_replace("\\","",$json);
    $v = json_decode($json);
    
    
    echo "\n\n";
    
    $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
    
    $request = $connection->sendRequest('text',array('file_name'=> $v->file_path ));
    
    if(!$request->isError())
    {
        //$r = runkit_lint( $request->result );
        $code = $request->result;
        
        $r = checkSyntaxPhp($code,$v->file_path);
        
        
        if(!empty($r) )
        {
            echo "Inside Error";
            
            //Call API to mark error spot on editor
            $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
    
            $request = $connection->sendRequest('setMarkers',array('start' =>  $e->getStartLine(), 
                                                            'end'  => $e->getEndLine(),
                                                            'file_name' => $filename
                                                              ));
            
            echo "Calling finised";
        }
        
        echo "R: ".$r ."\n\n";
    }
    else
    {
        print 'Error '.$request->error.': '.$request->errorMessage;
        var_dump($request->errorData);
    }
    
    //echo "\n\nDone: Checking Syntax of PHP File\n\n";
    
    
    
}

