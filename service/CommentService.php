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
	 * {"returnCode":0} ($msg中bookId，userId，content缺少，或者抛出异常，或者该图书已被删除)
	 * {"returnCode":1}  (添加成功)
	 * 
	 */
	public function addComment($msg) {
		$returnMsg = array();
		try{
			if (isset($msg->{"bookId"}) && isset($msg->{"userId"}) && isset($msg->{"content"})) {
				$bookId = $msg->{"bookId"};
				$authorId = $msg->{"userId"};
				$content = $msg->{"content"};
				$currentTime = date("y-m-d h:i:s",time());
				
				$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
				mysql_select_db("taoshusysu_db", $con);
				mysql_query("set names 'utf8'");
				/*查询bookId对应的图书是否存在*/
				$sql = "SELECT * FROM bookinfo WHERE bookId = '$bookId'";
				$result = mysql_query($sql);
				if (! $row = mysql_fetch_array($result)) {
					$returnMsg["returnCode"] = 0;
					return $returnMsg;
				}
				/*插入数据*/
				mysql_query("INSERT INTO comment (bookId, authorId,content,time)
					VAlUES ('$bookId', '$authorId','$content','$currentTime')", $con);
				/*获取id*/
				$commentId = mysql_insert_id();
				
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
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
	}
	
	/**
	 * 处理获取评论
	 * @param  $msg
	 * {"returnCode":0} ($msg中bookId缺少，或者抛出异常)
	 * {"returnCode":1,"commentList":[{"bookId":bookId,"authorId":authorId,"content":content,"time":time,"authorName":authorName},......]}  (获取成功,返回commentList数组)
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
					$tempComment = array();
					$authorId = $row["authorId"];
					$tempComment["bookId"] = $row["bookId"];
					$tempComment["authorId"] = $row["authorId"];
					$tempComment["content"] = $row["content"];
					$tempComment["time"] = $row["time"];
					
					/*获取作者的名字*/
					$sql_2 = "SELECT * FROM userinfo WHERE userId = '$authorId'";
					$result_2 = mysql_query($sql_2);
					if ($row_2 = mysql_fetch_array($result_2)) {
						$tempComment["authorName"] = $row_2["userName"];
					}
					array_push($tempList, $tempComment);
				}
				
				$returnMsg["commentList"] = $tempList;
				mysql_close($con);
				return $returnMsg;
			}
			else {
				$returnMsg["returnCode"] = 0;
				return $returnMsg;
			}
		}
		catch (Exception $e) {
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
	}
}
?>