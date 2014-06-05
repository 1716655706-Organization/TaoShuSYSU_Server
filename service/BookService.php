<?php

include_once 'Service.php';

header("content-type:text/html; charset=utf-8");

class BookService extends Service{
	/**
	 * 用于注册Service的Id
	 */
	public static $SERVICE_ID = 1;
	
	/**
	 * 用户注册命令的Id
	 */
	private static $ADDBOOK_ID = 0;
	private static $GETBOOKSINFO_ID = 1;
	private static $GETLABELS_ID = 2;
	/**
	 * 构造函数，在这里注册相应命令
	 */
	public function BookService() {
		$this->register(self::$ADDBOOK_ID, "addBookInfo");
		$this->register(self::$GETBOOKSINFO_ID, "getBooksInfo");
		$this->register(self::$GETLABELS_ID, "getLabelsByBookId");
	}
	
	/**
	 * 发布图书信息
	 * @param $msg
	 */
	public function addBookInfo($msg) {
		$returnMsg = array();
try {
		if (isset($msg->{"bookName"}))  {
			$bookId;
			$bookName = $msg->{"bookName"};
			$authorId = $msg->{"userId"};
			$labelArr = $msg->{"labelArr"};
			$content = $msg->{"content"};
			$currentTime = date("y-m-d h:i:s",time());
			
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
			
			/*插入图书信息*/
			mysql_query("INSERT INTO bookinfo (bookName,authorId,content, time)
				VAlUES ('$bookName', '$authorId','$content','$currentTime')", $con);
			
			/*查询刚插入图书信息的id*/
			$bookId = mysql_insert_id();
			
			/*插入数据*/
			for ($i = 0; $i < count($labelArr); $i++) {
				$labelContent = $labelArr[$i];
				mysql_query("INSERT INTO label (bookId, content)
					VAlUES ('$bookId', '$labelContent')", $con);
			}
			mysql_close($con);
			
			$returnMsg["returnCode"] = 1;
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
	 * 获取图书信息
	 */
	public function getBooksInfo($msg) {
		$returnMsg = array();
try {
		$startBookId = $msg->{"startBookId"};
		$size = $msg->{"size"};
		
		if (-1 == $startBookId)  {
			$returnMsg["returnCode"] = 1;
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
				
			/*查询图书信息*/
			$sql = "SELECT * FROM bookinfo ORDER BY time DESC, bookId DESC LIMIT $size ";
			$result = mysql_query($sql);
			$tempList = array();
			while($row = mysql_fetch_array($result)) {
				$tempMsg = array();
				$tempMsg["bookId"] = $row["bookId"];
				$tempMsg["bookName"] = $row["bookName"];
				$tempMsg["authorId"] = $row["authorId"];
				$authorId = $row["authorId"];
				$tempMsg["content"] = $row["content"];
				$tempMsg["time"] = $row["time"];
				
				/*获取作者的名字*/
				$sql_2 = "SELECT * FROM userinfo WHERE userId = '$authorId'";
				$result_2 = mysql_query($sql_2);
				if ($row_2 = mysql_fetch_array($result_2)) {
					$tempMsg["authorName"] = $row_2["userName"];
				}
				array_push($tempList, $tempMsg);
				
			}
			$returnMsg["bookList"] = $tempList;
			mysql_close($con);
			return $returnMsg;
		}
		else{
			$returnMsg["returnCode"] = 1;
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
				
			/*查询图书信息*/
			/*获取startBookId对应的时间戳*/
			$time;
			$sql = "SELECT * FROM bookinfo WHERE bookid = '$startBookId'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_array($result))  {
				$time = $row["time"];
			}
			
			$sql = "SELECT * FROM bookinfo WHERE bookId < $startBookId ORDER BY time DESC LIMIT $size";
			$result = mysql_query($sql);
			$tempList = array();
			while($row = mysql_fetch_array($result)) {
				$tempMsg = array();
				$tempMsg["bookId"] = $row["bookId"];
				$tempMsg["bookName"] = $row["bookName"];
				$tempMsg["authorId"] = $row["authorId"];
				$authorId = $row["authorId"];
				$tempMsg["content"] = $row["content"];
				$tempMsg["time"] = $row["time"];
				
				/*获取作者的名字*/
				$sql_2 = "SELECT * FROM userinfo WHERE userId = '$authorId'";
				$result_2 = mysql_query($sql_2);
				if ($row_2 = mysql_fetch_array($result_2)) {
					$tempMsg["authorName"] = $row_2["userName"];
				}
				array_push($tempList, $tempMsg);
				
			}
			$returnMsg["bookList"] = $tempList;
			mysql_close($con);
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
	 * 用图书id获取标签
	 */
	public function getLabelsByBookId($msg) {
		$returnMsg = array();
try{
		if (isset($msg->{"bookId"}))  {
			$labelArr = array();
			$bookId = $msg->{"bookId"};
			
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
				
			/*查询标签信息*/
			$sql = "SELECT * FROM label WHERE bookId = '$bookId'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_array($result)) {
				array_push($labelArr, $row["content"]);
			}
			
			mysql_close($con);
				
			$returnMsg["returnCode"] = 1;
			array_push($returnMsg, $labelArr);
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