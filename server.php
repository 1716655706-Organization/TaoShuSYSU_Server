<?php

include_once 'Dispatcher.php';

header("content-type:text/html; charset=utf-8");
try {
	$dispatcher = new Dispatcher();
	
 	//$msg = json_decode('{"sid":0, "cid":0, "userName":"wyl", "password":"wyl"}');
 	$msg = json_decode($_POST["msg"]);
 	$returnMsg = $dispatcher->dispatch($msg);
 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":0, "cid":0, "userName":"孙飞", "password":"8"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":0, "cid":0, "userName":"李四", "password":"8"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":0, "cid":0, "userName":"王五", "password":"8"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":0, "cid":0, "userName":"赵六", "password":"8"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":0, "cid":1, "userName":"赵延霞", "password":"wyl"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":1, "cid":0, "bookName":"高等数学", "userId":5, "labelArr":["高等数学","高数"], "content":"高等数学教材"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":1, "cid":0, "bookName":"鸟哥的Linux私房菜", "userId":7, "labelArr":["Linux","鸟哥"], "content":"linux教程"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":1, "cid":0, "bookName":"UML和模式应用", "userId":8, "labelArr":["系统分析与设计","UML","建模语言"], "content":"这是一本基本的UML教材"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":1, "cid":0, "bookName":"大学英语词汇", "userId":10, "labelArr":["四级","英语单词","词汇","英语四级"], "content":"这是一本英语词汇学习教材"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":1, "cid":1, "startBookId":-1, "size":5}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":1, "cid":1, "startBookId":5, "size":2}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":1, "cid":2, "bookId":5}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);

	
// 	$msg = json_decode('{"sid":1, "cid":3, "authorId":2,"startBookId":-1,"size":4}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);

// 	$msg = json_decode('{"sid":1, "cid":4, "bookName":"英语","startBookId":-1,"size":10}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);

// 	$msg = json_decode('{"sid":1, "cid":5, "label":"系统分析","startBookId":-1,"size":5}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);

// 	$msg = json_decode('{"sid":1, "cid":6, "bookId":"1"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
// 	$msg = json_decode('{"sid":2, "cid":0, "bookId":2, "userId":1, "content":"现在正急需这本书，求联系"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":2, "cid":0, "bookId":1, "userId":2, "content":"现在正急需这本书，求联系"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":2, "cid":0, "bookId":1, "userId":3, "content":"现在正急需这本书，求联系"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":2, "cid":0, "bookId":1, "userId":4, "content":"现在正急需这本书，求联系"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":2, "cid":0, "bookId":1, "userId":5, "content":"现在正急需这本书，求联系"}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
// 	$msg = json_decode('{"sid":2, "cid":1, "bookId":5}');
// 	$returnMsg = $dispatcher->dispatch($msg);
// 	echo json_encode($returnMsg);
	
	
}
catch (Exception $e) {
	$returnMsg = array();
	$returnMsg["returnCode"] = 0;
	echo $returnMsg;
}

?>