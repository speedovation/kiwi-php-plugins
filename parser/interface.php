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


  function send()
    {
        //$api = new \KiWiApi(); 
        
        $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
		$connection->setTimeout(60);
		
		//$request = $connection->sendRequest('status_message', ['Limit Message',30]);
		
		//$request = $connection->sendRequest('flash_message', ["Archana Some FLASH"]);
		//$request = $connection->sendRequest('open_untitled', ["Python File","var i = 0"]);
		
		//$request = $connection->sendRequest('arch', []);
		
		

		//$request = $connection->sendRequest('register_event', ['/home/yash/Projects/kiwi/Build/Debug/resources/snippets/html/base/html','text_changed','php interface.php cool']);
		
		$request = $connection->sendRequest('register_event_type', ['Php File','text_changed','php interface.php cool']);
		
		#$request = $connection->sendRequest('register_event_pattern', ['.php,.php5','text_changed','php interface.php cool']);
		
		#$request = $connection->sendRequest('register_event_path', ['/home/yash/Projects/kiwi/Build/Debug/resources/snippets/html/base/html','text_changed','php interface.php cool']);
		
		
		

        
        echo "\nJSON ".$request->result;
        
        //$api->callApi( 'updateAutocompleteModel', [$this->json] );
    }
