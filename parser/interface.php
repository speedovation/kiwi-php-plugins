<?php

require "parser.php";
require __DIR__."/../vendor/autoload.php";

//These lines will be used by IDE to call required functions
if(function_exists( $argv[1] ))
  call_user_func($argv[1]);

 		
		

function parsePhp()
{
    $parser = new \KiWi\KiwiParser(TRUE);
    /*$parser->processDir('/home/yash/Projects/php/laravel/',["php"])*/

   $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));

	
	
	$request = $connection->sendRequest('filepath', []);
    echo "Result:".$request->result;
    
    //"/var/www/html/kiwi/app/controllers/HomeController.php"
    $parser->processFile($request->result)                 
       ->call()
       ->send();
}


function parsePhpProject()
{
	    $parser = new \KiWi\KiwiParser(TRUE);
    /*$parser->processDir('/home/yash/Projects/php/laravel/',["php"])*/
    $parser
       ->processDir("/home/yash/Projects/php/laravel",
                        [
                            "include"=>"*.php" , 
                            "exclude" =>""
                        ],
                        [
                            "files" => ["/home/yash/Projects/php/laravel/bootstrap/compiled.php"],
                            "dirs" => ["/home/yash/Projects/php/laravel/bootstrap","* /test/*"]
                        ])
       
       ->call()
       ->send();
}
