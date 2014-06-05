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
	
	/**
	 * 处理获取评论
	 * @param  $msg
	 * @return multitype:number
	 */
	public function getCommentByBookId($msg){
		$returnMsg = array();
		if (isset($msg->{"bookId"})) {
			$bookId = $msg->{"bookId"};
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
			
			/*是否存在*/
			$sql ="SELECT * FROM comment WHERE bookId = '$bookId'";
			$result= mysql_query($sql);
			mysql_close($con);
			while ($row = mysql_fetch_array($result)) {
				if ($userName == $row["userName"] && $password == $row["password"]) {
					$returnMsg["returnCode"] = 1;
					$returnMsg["userId"] = $row["userId"];
					return $returnMsg;
				}
				else {
					$returnMsg["returnCode"] = -1;
					return $returnMsg;
				}
			}
		}
		else {
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
	}
}
?>