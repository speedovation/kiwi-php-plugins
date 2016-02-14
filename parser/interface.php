<?php

require "parser.php";
require __DIR__."/../vendor/autoload.php";

//These lines will be used by IDE to call required functions
if(function_exists( $argv[1] ))
  call_user_func($argv[1], $argv[2]);




 //print_r($argv[2]);

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

function parsePhp($json)
{
     //echo $json , PHP_EOL;
    //Get path and other required items from json
    $json = str_replace("'","",$json);
    $json = str_replace("\\","",$json);
    $v = json_decode($json);

    //echo 'Last error: ', json_last_error(), PHP_EOL, PHP_EOL;
    //print_r($v);


    $parser = new KPhpParser();
    //Pass a valid file
	 $parser->parseFile($v->project_path,$v->file_path);

    echo "Done: Parsing PHP File";

    /*$json = str_replace("'","",$json);//  return;

    //$json = removeslashes($json);
    //var_dump( $json  );

    $v = json_decode($json);
	 //echo 'Last error: ', $json_errors[json_last_error()], PHP_EOL, PHP_EOL;
    //print_r($v->file_path);


    $parser = new \KiWi\KiwiParser(TRUE);
    //$parser->processDir('/home/yash/Projects/php/laravel/',["php"])

   //$connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));



    //"/var/www/html/kiwi/app/controllers/HomeController.php
        $j=  $parser->processFile( $v->file_path )
       ->call()
       ->result();

    //echo $j."kkk";
    echo return_result('updateAutocompleteModel', $j);*/

}


function parsePhpProject($json)
{
    //echo $json , PHP_EOL;
    //Get path and other required items from json
    $json = str_replace("'","",$json);
    $json = str_replace("\\","",$json);
    $v = json_decode($json);

    //echo 'Last error: ', json_last_error(), PHP_EOL, PHP_EOL;
    //print_r($v);


    $parser = new KPhpParser();
    //Pass a valid file
	 $parser->parseDir($v->project_path);


    echo "Done: Parsing PHP Project";
/*        $parser = new \KiWi\KiwiParser(TRUE);*/
    /*$parser->processDir('/home/yash/Projects/php/laravel/',["php"])*/
   /* $parser
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
       ->send();*/
}


//We are not using this just an example we can register events from code or json file

  function registerEventExample()
    {
        //$api = new \KiWiApi();

        try{

            $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
            $connection->setTimeout(60);

            //$request = $connection->sendRequest('status_message', ['Limit Message',30]);

            //$request = $connection->sendRequest('flash_message', ["Archana Some FLASH"]);
            //$request = $connection->sendRequest('open_untitled', ["Python File","var i = 0"]);

            //$request = $connection->sendRequest('arch', []);



            //$request = $connection->sendRequest('register_event', ['/home/yash/Projects/kiwi/Build/Debug/resources/snippets/html/base/html','text_changed','php interface.php cool']);

            $request1 = Tivoka\Client::request('register_event_type', ['1_type' => 'Php File',
                                                                  '2_signal' => 'on_text_changed',
                                                                        '3_command' => 'php interface.php cool'
                                                                        ]);


            $request2 = Tivoka\Client::request('register_event_type', ['1_type' => 'Php File',
                                                                        '2_signal' => 'on_text_changed',
                                                                        '3_command' => 'php interface.php some'
                                                                        ]);

            $request3 = Tivoka\Client::request('register_event_pattern', ['php:php5','on_text_changed','php interface.php tuk']);

            #$connection->send($request1 , $request2, $request3);

            $str = 'register_event_pattern|php:php5,on_text_changed,php interface.php tuk;';
            $str .= 'register_event_type|Php File,on_text_changed,php interface.php some;';

            $request4 = Tivoka\Client::request('register_event', [$str]);


            $connection->send( $request4 );


            #$request = $connection->sendRequest('register_event_path', ['/home/yash/Projects/kiwi/Build/Debug/resources/snippets/html/base/html','text_changed','php interface.php cool']);





            #echo "\nJSON ".$request->result;

        }
        catch (Tivoka\Exception\ConnectionException $e)
        {
            echo "\nIDE is not running\n\n";
        }

        //$api->callApi( 'updateAutocompleteModel', [$this->json] );
    }
