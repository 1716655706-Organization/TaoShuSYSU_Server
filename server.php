<?php

include_once 'Dispatcher.php';
try {
	$dispatcher = new Dispatcher();
	$msg = json_decode('{"sid":0, "cid":0, "userName":"wyl", "password":"wyl"}');
	$returnMsg = $dispatcher->dispatch($msg);
	echo json_encode($returnMsg);
}
catch (Exception $e) {
	echo $e;
}

?>