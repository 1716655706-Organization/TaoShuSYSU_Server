<?php

include_once 'Service.php';

header("content-type:text/html; charset=utf-8");

class CommentService extends Service{
	/**
	 * 用于注册Service的Id
	 */
	public static $SERVICE_ID = 2;
	
	/**
	 * 用户注册命令的Id
	 */
	private static $ADDCOMMENT_ID = 0;
	private static $GETCOMMENT_ID = 1;
	/**
	 * 构造函数，在这里注册相应命令
	 */
	public function CommentService() {
		$this->register(self::$ADDCOMMENT_ID, "addComment");
		$this->register(self::$GETCOMMENT_ID, "getCommentsByBookId");
	}
	
	/**
	 * 添加评论
	 * @param $msg
	 */
	public function addComment($msg) {
		$returnMsg = array();
try{
		if (isset($msg->{"bookId"}) && isset($msg->{"userId"})) {
			$bookId = $msg->{"bookId"};
			$authorId = $msg->{"userId"};
			$content = $msg->{"content"};
			$currentTime = date("y-m-d h:i:s",time());
			
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
			
			/*插入数据*/
			mysql_query("INSERT INTO comment (bookId, authorId,content,time)
				VAlUES ('$bookId', '$authorId','$content','$currentTime')", $con);
			/*获取id*/
			$commentId = mysql_insert_id();
			echo $commentId;
			
			$returnMsg["returnCode"] = 1;
			$returnMsg["commentId"] = $commentId;
				
			return $returnMsg;
		}
		else {
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
		}
catch (Exception $e) {
	$returnMsg = array();
	$returnMsg["returnCode"] = 0;
	echo $returnMsg;
}
	}
	
	/**
	 * 处理获取评论
	 * @param  $msg
	 */
	public function getCommentsByBookId($msg){
		$returnMsg = array();
try{
		if (isset($msg->{"bookId"})) {
			$returnMsg["returnCode"] = 1;
			$bookId = $msg->{"bookId"};
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
			
			/*是否存在*/
			$sql ="SELECT * FROM comment WHERE bookId = '$bookId'";
			$result= mysql_query($sql);
			
			$tempList = array();
			while ($row = mysql_fetch_array($result)) {
				$tempCommit = array();
				$authorId = $row["authorId"];
				$tempCommit["authorId"] = $row["authorId"];
				$tempCommit["content"] = $row["content"];
				$tempCommit["time"] = $row["time"];
				
				/*获取作者的名字*/
				$sql_2 = "SELECT * FROM userinfo WHERE userId = '$authorId'";
				$result_2 = mysql_query($sql_2);
				if ($row_2 = mysql_fetch_array($result_2)) {
					$tempCommit["authorName"] = $row_2["userName"];
				}
				array_push($tempList, $tempCommit);
			}
			
			$returnMsg["commitList"] = $tempList;
			mysql_close($con);
			return $returnMsg;
		}
		else {
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
		}
catch (Exception $e) {
	$returnMsg = array();
	$returnMsg["returnCode"] = 0;
	echo $returnMsg;
}
	}
}
?>