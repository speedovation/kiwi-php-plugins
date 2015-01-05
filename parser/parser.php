<?php

namespace KiWi;

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
    
    public function __construct()
    {
        $this->broker = new \TokenReflection\Broker(new \TokenReflection\Broker\Backend\Memory());
        //$broker->processDirectory('/home/yash/Projects/qt/kiwi/Build/debug/resources/php/api');
    }
    
    public function processDir($dirPath)
    {
        $this->broker->processDirectory($dirPath);
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
                echo $method->getName();
                print_r( $method->getAnnotations() );
                
                
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
        
        $this->json = json_encode($classesArr);     
        
        return $this;  
    }
    
    public function send()
    {
        $api = new \KiWiApi(); 
        
        $api->callApi( 'updateAutocompleteModel', [$this->json] );
    }
}


//Calling this

$parser = new KiwiParser();
$parser->processDir('/home/yash/Projects/php/laravel/vendor/monolog/',["*.php"])
       ->call()
       ->send();


//$function = $broker->getFunction(...);
//$constant = $broker->getConstant(...);


