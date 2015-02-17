<?php
/**
* KiWiApi
*
* Base API for KiWi IDE - KineticWing.com!
*
* NOTICE OF LICENSE
*
* Licensed under the Apache
*
* @package KiWiApi
* @author Yash B <yash@speedovation.com>
* @license http://www.apache.org/licenses/LICENSE-2.0
* @link http://speedovation.com
* @version 1.0.10
*/

/**
* The main KiWiApi class
*
* @see KiWiApi::highlight()
*
* @author Yash B <yash@speedovation.com>
* @since 1.0.0
*/
class KiWiApi
{
    /**
    * IDE is listening at this port.
    *
    * @since 1.0
    * @var int
    */
    private $port = 9040;
    
    /**
    * IDE is listening at this address
    *
    * @since 1.0
    * @var string
    */
    private $address = "127.0.0.1";
    
    /**
    * Hold socket instance
    *
    * @since 1.0
    * @var Socket
    */
    private $socket;
    
    
    /**
    * Class options
    *
    * @access protected
    * @since 1.0.0
    * @var integer
    */
    protected $options = 0;
    
    private $PRINT_DEBUG;
    private $PRINT_INFO;
    
    
    /**
    * Create and connect socket
    * @param $debug
    * @access public
    * @since 1.0.0
    */
    public function __construct($info = TRUE,$debug = FALSE)
    {
        // No Timeout
        set_time_limit(0);
        
        $this->PRINT_DEBUG = $debug;
        $this->PRINT_INFO = $info;
        
        /*		$this->createSocket();
        $this->connectSocket();*/
    }
    
    function __destruct()
    {
        if($this->PRINT_INFO)
        echo "Closing socket...";
        
        socket_close($this->socket);
        
        if($this->PRINT_INFO)
        echo "OK.\n\n";
    }
    
    private function createSocket()
    {
        
        /* Create a TCP/IP socket. */
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->socket === false)
        {
            
            if($this->PRINT_INFO)
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        }
        else
        {
            if($this->PRINT_INFO)
            echo "OK.\n";
        }
        
    }
    
    private function connectSocket()
    {
        if($this->PRINT_INFO)
        echo "Attempting to connect to '$this->address' on port '$this->port'...";
        
        $result = socket_connect($this->socket, $this->address, $this->port);
        
        if ($result === false)
        {
            if($this->PRINT_INFO)
            echo "socket_connect() failed.\nReason: ($result) "
            . socket_strerror(socket_last_error($this->socket)) . "\n";
        }
        else
        {
            if($this->PRINT_INFO)
            echo "OK.\n";
            
        }
    }
    
    
    /**
    * Create array in proper and required format and sends it to IDE
    *
    * If IDE is directly calling then submit to socket just return JSON
    *
    * @return string
    * @param $name some details
    * @param $args
    *
    * @access private
    * @since 1.0.0
    */
    public function callApi($name, array $args, $dispatch = FALSE, $return = TRUE)
    {
        
        $newArgs = [];
        $i=1;
        foreach($args as $arg )
        {
            $newArgs[$i++."_".gettype($arg)] = $arg;
        }
        
        
        $b = [
        "id"       => "81093c7a-8093-4303-a15a-fd97b863164c",
        "method"   => $name,
        "params"   => $newArgs,
        "return" => $return,
        ];
        
        if($this->PRINT_DEBUG)
        print_r($b);
        
        if($dispatch)
        {
            echo json_encode($b);
            return;
        }
        
        
        
        
        $this->send($b);
    }
    
    /**
    * Takes array as input which is created in proper format and sends to IDE API using socket
    *
    * @return string
    *
    * @access private
    * @since 1.0.0
    */
    private function send($input)
    {
        $in = json_encode($input);
        
        if($this->PRINT_DEBUG)
        print_r($in);
        
        if($this->PRINT_INFO)
        echo "Sending HTTP HEAD request...";
        
        //echo "length ".strlen($in);
        //$g = socket_write($this->socket, $in, strlen($in));
        
        /*        $g = socket_sendto ( $this->socket , $in , strlen($in) , MSG_EOF , $this->address, $this->port);*/
        $this->sendall($in);
        
        while ( $out = socket_read($this->socket, $this->port) )
        {
            return $out;
        }
        
    }
    
    private function sendall($input)
    {
        
        $offset=0;
        $length = strlen($input);
        
        
        do
        {
            $this->createSocket();
            $this->connectSocket();
            
            echo "send length and offset ".$length." ".$offset;
            
            $offset += socket_sendto ( $this->socket , $input , $length - $offset , MSG_OOB , $this->address, $this->port);
            
            echo "after send length and offset ".$length." ".$offset;
            
            
        } while($offset < $length);
        
        return $offset;
    }
    
    
}
