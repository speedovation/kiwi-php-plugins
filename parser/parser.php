<?php

namespace KiWi;

use Symfony\Component\Finder\Finder;

require ("../vendor/autoload.php");
require '../api/KiWiApi.php';




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
    
    public function __construct()
    {
        

        $this->finder = new Finder();
        $this->broker = new \TokenReflection\Broker(new \TokenReflection\Broker\Backend\Memory());
        //$broker->processDirectory('/home/yash/Projects/qt/kiwi/Build/debug/resources/php/api');
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
             
            try
            {      
                $this->broker->processFile($file->getRealpath(), FALSE);
            } 
            catch (\TokenReflection\Exception\ParseException $e) 
            {
                echo "\nParse Error on".$file->getRealpath();
            } 
            catch (\TokenReflection\Exception\StreamException $e) 
            {
                echo "\nStream Error on".$file->getRealpath();
            } 
            catch (\TokenReflection\Exception\FileProcessingException $e) 
            {
                echo "\nFile Processing Error on".$file->getRealpath();
                echo "\nMessage:".$e->getDetail()."\n";
            } 
            catch (\TokenReflection\Exception\BrokerException $e) {
                echo "\nBrokerException Error on".$file->getRealpath();
            }
            
        }
        
         
        //$this->broker->processDirectory($dirPath);
        
        return $this;
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
                    $parametersArr[$parameter->getName()] = [
                     
                     "default" => ( $parameter->isDefaultValueAvailable( ) ?  $parameter->getDefaultValue() : '' ),
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
        
        return $this;  
    }
    
    public function send()
    {
        $api = new \KiWiApi(); 
        
        echo "JSON".$this->json;
        
        $api->callApi( 'updateAutocompleteModel', [$this->json] );
    }
}


//Calling this

    $parser = new KiwiParser();
    /*$parser->processDir('/home/yash/Projects/php/laravel/',["php"])*/
    $parser->processDir('/home/yash/Projects/php/laravel',
                        [
                            "include"=>"*.php" , 
                            "exclude" =>""
                        ],
                        [
                            "files" => ["/home/yash/Projects/php/laravel/bootstrap/compiled.php"],
                            "dirs" => ["/home/yash/Projects/php/laravel/bootstrap","*/test/*"]
                        ])
       ->call()
       ->send();


//$function = $broker->getFunction(...);
//$constant = $broker->getConstant(...);


