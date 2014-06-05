<?php

include_once 'Dispatcher.php';

header("content-type:text/html; charset=utf-8");
try {
	$dispatcher = new Dispatcher();
	
	//$msg = json_decode('{"sid":0, "cid":0, "userName":"wyl", "password":"wyl"}');
	$msg = json_decode($_POST["msg"]);
	$returnMsg = $dispatcher->dispatch($msg);
	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":0, "cid":1, "userName":"wyl", "password":"wyl"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":1, "cid":0, "bookName":"计算机", "userId":1, "labelArr":["jisuanji","电脑","科技"], "content":"这是一本基本的计算机教材"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":1, "cid":1, "startBookId":6, "size":1}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
// 	echo "\n";
	
	
// 	$msg = json_decode('{"sid":1, "cid":1, "startBookId":5, "size":2}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":1, "cid":2, "bookId":5}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":2, "cid":0, "bookId":5, "userId":1, "content":"现在正急需这本书，求联系"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
	
	
}
catch (Exception $e) {
	echo $e;
}

?>