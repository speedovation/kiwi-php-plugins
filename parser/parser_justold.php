<?php

namespace KiWi;

use Symfony\Component\Finder\Finder;

require __DIR__."/../vendor/autoload.php";
require __DIR__.'/../api/KiWiApi.php';




/**
{
    "classes": {
        "classname": {
            "namespace": "",
            "functions": {
                "functionname": {
                    "doccomment": "",
                    "anotations": {},
                    "parameters": {}
                },
                "functionname_": {
                    "doccomment": "",
                    "anotations": {},
                    "parameters": {}
                }
            }
        }
    }
}
*/

//For first version forget namespace


class KiwiParser
{
    private $broker;
    private $json;
    private $finder;
    private $debug;
    
    public function __construct($debug = FALSE)
    {
        $this->debug = $debug;

        $this->finder = new Finder();
        $this->broker = new \TokenReflection\Broker(new \TokenReflection\Broker\Backend\Memory());
        //$broker->processDirectory('/home/yash/Projects/qt/kiwi/Build/debug/resources/php/api');
    }
    
    public function processFile($filePath)
    {
        try
            {      
               $this->broker->processFile($filePath, FALSE);
            } 
            catch (\TokenReflection\Exception\ParseException $e) 
            {
                if($this->debug)
                    echo "\nParse Error on".$filePath;
            } 
            catch (\TokenReflection\Exception\StreamException $e) 
            {
                if($this->debug)
                    echo "\nStream Error on".$filePath;
            } 
            catch (\TokenReflection\Exception\FileProcessingException $e) 
            {
                if($this->debug)
                {
                    echo "\nFile Processing Error on".$filePath;
                    echo "\nMessage:".$e->getDetail()."\n";
                }
            } 
            catch (\TokenReflection\Exception\BrokerException $e) 
            {
                if($this->debug)
                    echo "\nBrokerException Error on".$filePath;
            }
            
            return $this;
            
    }
    
    public function processDir($dirPath, $filters = [], $excludes = [])
    {
       
        $this->finder->in($dirPath)
                    ->exclude($excludes['dirs'])
                    ->ignoreVCS(false)
                    ->ignoreUnreadableDirs()
                    ->ignoreDotFiles(TRUE)
                    ->name($filters['include'])
                    ->notName($filters['exclude'])
                    ;
        
        foreach($this->finder as $file)
        {
            
            if(in_array($file,$excludes['files']))
                continue;
            
                $this->processFile($file->getRealpath());    
            
        }
        
         
        //$this->broker->processDirectory($dirPath);
        
        return $this;
    }
    
    public function arc()
    {
        //
    }
    
    public function call()
    {

        /*$class = $broker->getClass('Zend_Version'); // returns a TokenReflection\ReflectionClass instance*/
        
        $classes = $this->broker->getClasses();
        
        /*$keys = array_keys( $data);
        
        $values = array_values($data);
        
        print_r($keys);
        
        $api = $values[0];
        */
        
        $classesArr = [];
        
        foreach($classes as $class)
        {
            
            $methods = $class->getMethods();
            
            
            //print_r($methods);
            $functionsArr = [];
            
            foreach ($methods as $method)
            {
                //echo $method->getName()."\n";
                //print_r( $method->getAnnotations() );
                
                
                $parameters = $method->getParameters();
                $parametersArr = [];
                foreach ($parameters as $parameter)
                {
                    $val  = ( $parameter->isDefaultValueAvailable( ) ? $parameter->getDefaultValue() : '' );
                    if(!is_array($val))
                        $val = (string) $val;
                    
                    $parametersArr[$parameter->getName()] = [
                     
                     
                     "default" => $val,
                     "isNull" => $parameter->allowsNull( ),	
                     "isOptional" => $parameter->isOptional( ),
                     "isPassedByReference" => $parameter->isPassedByReference( ),
                     "canBePassedByValue" => $parameter->canBePassedByValue( ),
                     "isArray" => $parameter->isArray()
                     
                    ];
                    
                }
                
                $functionsArr[$method->getName()] = [
                    "parameters" => $parametersArr,
                    "annotations" => $method->getAnnotations() 
                ];
                
            
                
            }
            
            $classesArr[$class->getShortName()] = [
                "namespace" => $class->getNamespaceName(),
                "functions" => $functionsArr
                
                
                ];
            
        } 
        
        //print_r($classesArr);
        
        $this->json = json_encode($classesArr);     
/*        echo "\nERROR". json_last_error() ;*/
/*        echo json_last_error_msg();*/
        return $this;  
    }
    
    public function send()
    {
        //$api = new \KiWiApi(); 
        
        $connection = \Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
		
		$request = $connection->sendRequest('updateAutocompleteModel', [$this->json]);
		/*$request = $connection->sendRequest('showFlash', ["Some FLASH"]);*/


        
        echo "\nJSON ".$this->json;
        
        //$api->callApi( 'updateAutocompleteModel', [$this->json] );
    }
    
    public function result()
    {
		return $this->json;
	}
}

/*$directories = glob("/home/yash/Projects/php/laravel/vendor/patchwork/utf8/class/Patchwork/PHP/" . '/*' , GLOB_ONLYDIR);
//Calling this

     foreach($directories as $dir)
        {
            */
    
             
            


       
/*       echo $dir."\n=======================================\n\n\n";
       
       }
*/

//$function = $broker->getFunction(...);
//$constant = $broker->getConstant(...);


