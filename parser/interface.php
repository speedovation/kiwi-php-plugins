<?php

require "parser.php";


//These lines will be used by IDE to call required functions
if(function_exists( $argv[1] ))
  call_user_func($argv[1]);


function parsePhp()
{
    $parser = new \KiWi\KiwiParser(TRUE);
    /*$parser->processDir('/home/yash/Projects/php/laravel/',["php"])*/

    $parser->processFile("/home/yash/Projects/qt/kiwi/Build/debug/resources/php/parser/parser.php")                 
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

?>
