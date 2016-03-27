<?php

use PhpParser\Error;
use PhpParser\ParserFactory;


function checkSyntaxPhp($code)
{

    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    
    try 
    {
        print $code. "  \n\n";
        $stmts = $parser->parse($code);
        // $stmts is an array of statement nodes
        return "";
    } 
    catch (PhpParser\Error $e) 
    {
        
        /*$errors = $parser->getErrors();

        foreach ($errors as $error) 
        {
            // $error is an ordinary PhpParser\Error
           // echo "PParse Error: ". $e->getMessage();
            
            //if( $error->hasColumnInfo() ) 
            {
                echo $error->getRawMessage() . 
                    ' from ' . $error->getStartLine() . 
                    //':' . $error->getStartColumn($code) . 
                    ' to ' . $error->getEndLine() ;//. 
                    //':' . $error->getEndColumn($code);
            }
        
            
        }*/


        /*if( $e->hasColumnInfo() ) 
        {
            echo $e->getRawMessage() . 
                ' from ' . $e->getStartLine() . 
                ':' . $e->getStartColumn($code) . 
                ' to ' . $e->getEndLine() . 
                ':' . $e->getEndColumn($code);
        }*/
        
        //Call API to mark error spot on editor
        
        
        return "Parse Error: ". $e->getMessage(). 
                " - S: ". $e->getStartLine().
                " - E: ". $e->getEndLine();
    }

}
