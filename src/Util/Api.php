<?php

namespace KiWi\Util;

use KiWi\DependencyInjection\Application;

class Api
{
    protected $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    
    function hello()
    {
        echo "Hello Test";
    }
    
    function removeslashes($string)
    {
        $string=implode("",explode("\\",$string));
        return stripslashes(trim($string));
    }
    
    
    function return_result($method, $args)
    {
        return '{ "method" : "'.$method.'", "params" : '.$args.'}';
    }
    
    
    function call_kiwi($method, $args)
    {
        
        $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
        
        //$request = $connection->sendRequest('text',array('file_name'=> $v->file_path ));
		$request = $connection->sendRequest($method, $args );
        
        if(!$request->isError())
        {
            //$r = runkit_lint( $request->result );
            $r = $request->result;
            
			
			return $r;
           
        }
        else
        {
            print 'Error '.$request->error.': '.$request->errorMessage;
            var_dump($request->errorData);
			
			return "";
        }
    }
}
