<?php

require_once 'KiWiApi.php';

$api = new KiWiApi();

//Open file
$api->callApi( 'openFile', ['QString:/home/yash/Projects/xdebug/SublimeXdebug/Xdebug.py'] );


//Update current outline line
$api->callApi( 'updateOutlineModel', ['DATA', 'DATA', 0] );
$api->callApi( 'updateOutlineModel', ['ChildDATA1', 'DATA',1] );
$api->callApi( 'updateOutlineModel', ['ChildDATA2', 'DATA',1] );
$api->callApi( 'updateOutlineModel', ['ChildDATA3', 'DATA',2] );
$api->callApi( 'updateOutlineModel', ['ChildDATA4', 'DATA',2] );
$api->callApi( 'updateOutlineModel', ['InnnerChildDATA1', 'DATA', 3] );
 
//More are coming soon
