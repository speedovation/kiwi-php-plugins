<?php

require_once 'KiWiApi.php';
/*
$api = new KiWiApi();

//Open file
$api->callApi( 'openFile', ['QString:/home/yash/Projects/xdebug/SublimeXdebug/Xdebug.py'] );* /

$api->callApi( 'echo', ['DATA', 'DATA', 0] );

//Update current outline line
/*$api->callApi( 'updateOutlineModel', ['DATA', 'DATA', 0] );
$api->callApi( 'updateOutlineModel', ['ChildDATA1', 'DATA',1] );
$api->callApi( 'updateOutlineModel', ['ChildDATA2', 'DATA',1] );
$api->callApi( 'updateOutlineModel', ['ChildDATA3', 'DATA',2] );
$api->callApi( 'updateOutlineModel', ['ChildDATA4', 'DATA',2] );
$api->callApi( 'updateOutlineModel', ['InnnerChildDATA1', 'DATA', 3] );
*/ 
//More are coming soon

require __DIR__."/../vendor/autoload.php";



$connection = Tivoka\Client::connect(array('host' => '127.0.0.1', 'port' => 9040));
$request = $connection->sendRequest('filepath', [3]);
/*$request = $connection->sendRequest('showFlash', ["Some FLASH"]);*/
print $request->result;// 42
