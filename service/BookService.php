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
	private static $GETBOOKSBYUSERID = 3;
	private static $GETBOOKSBYBOOKNAME = 4;
	private static $GETBOOKSBYLABEL = 5;
	private static $DELETEBOOK = 6;
	/**
	 * 构造函数，在这里注册相应命令
	 */
	public function BookService() {
		$this->register(self::$ADDBOOK_ID, "addBookInfo");
		$this->register(self::$GETBOOKSINFO_ID, "getBooksInfo");
		$this->register(self::$GETLABELS_ID, "getLabelsByBookId");
		$this->register(self::$GETBOOKSBYUSERID, "getBooksByUserId");
		$this->register(self::$GETBOOKSBYBOOKNAME, "getBooksByBookName");
		$this->register(self::$GETBOOKSBYLABEL, "getBooksByLabel");
		$this->register(self::$DELETEBOOK, "deleteBook");
	}
	
	/**
	 * 发布图书信息
	 * @param  $msg
	 * @return $returnMsg 
	 * {"returnCode":0} ($msg中bookName、content、userId缺少，或者抛出异常)
	 * {"returnCode":1} (正常添加图书)
	 */
	public function addBookInfo($msg) {
		$returnMsg = array();
		try {
			if (isset($msg->{"bookName"}) && isset($msg->{"content"}) && isset($msg->{"userId"}) && isset($msg->{"labelArr"}))  {
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
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
	}
	
	/**
	 * 获取图书信息
	 * @param  $msg
	 * @return $returnMsg 
	 * {"returnCode":0} ($msg中startBookId、size缺少，或者抛出异常)
	 * {"returnCode":1,"bookList":[{"bookId":"", "bookName":"","authorId":"","content":"","time":"","authorId":""},......]}
	 */
	public function getBooksInfo($msg) {
		$returnMsg = array();
		try {
			if (isset($msg->{"startBookId"}) && isset($msg->{"size"})) {
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
	 * 用图书id获取标签
	 * @param  $msg
	 * @return $returnMsg 
	 * {"returnCode":0} ($msg中bookId缺少，或者抛出异常)
	 * {"returnCode":1,"bookList":[{"bookId":"", "bookName":"","authorId":"","content":"","time":"","authorId":""},......]}
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
				$returnMsg["labelArr"] =  $labelArr;
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
	 *根据用户id获取他的有关书籍
	 * @param  $msg
	 * @return $returnMsg 
	 * {"returnCode":0} ($msg中userId、startBookId、size缺少，或者抛出异常)
	 * {"returnCode":1,"bookList":[{"bookId":"", "bookName":"","authorId":"","content":"","time":"","authorId":""},......]}
	 */
	public function getBooksByUserId($msg) {
		$returnMsg = array();
		try {
			if (isset($msg->{"userId"}) && isset($msg->{"startBookId"}) && isset($msg->{"size"})) {
				$authorId = $msg->{"userId"};
				$authorName;
				$startBookId = $msg->{"startBookId"};
				$size = $msg->{"size"};
	
				if (-1 == $startBookId)  {
					$returnMsg["returnCode"] = 1;
					$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
					mysql_select_db("taoshusysu_db", $con);
					mysql_query("set names 'utf8'");
	
					/*获取用户名字*/
					$sql = "SELECT * FROM userinfo WHERE userId = '$authorId'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_array($result)) {
						$authorName = $row["userName"];
					}
							
					/*查询图书信息*/
					$sql = "SELECT * FROM bookinfo WHERE authorId = '$authorId' ORDER BY time DESC, bookId DESC LIMIT $size ";
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
						$tempMsg["authorName"] = $authorName;

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
					
					/*获取用户名字*/
					$sql = "SELECT * FROM userinfo WHERE userId = '$authorId'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_array($result)) {
						$authorName = $row["userName"];
					}
					
					
					/*获取startBookId对应的时间戳*/
					$time;
					$sql = "SELECT * FROM bookinfo WHERE bookid = '$startBookId'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_array($result))  {
						$time = $row["time"];
					}
					
					/*查询图书信息*/
					$sql = "SELECT * FROM bookinfo WHERE authorId = '$authorId' AND bookId < $startBookId ORDER BY time DESC LIMIT $size";
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
						$tempMsg["authorName"] = $authorName;

 						array_push($tempList, $tempMsg);
							
					}
					$returnMsg["bookList"] = $tempList;
					mysql_close($con);
					return $returnMsg;
				}
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
	 * 根据图书名搜索图书
	 * @param $msg
	 * @return returnMsg
	 * {"returnCode":0} ($msg中bookName、startBookId、size缺少，或者抛出异常)
	 * {"returnCode":1,"bookList":[{"bookId":bookId,"bookName":bookName,"authorId":authorId,"content":content,"time":time,"authorName":authorName},.....]} (成功获取，返回bookList)
	 */
	public function getBooksByBookName($msg){
		$returnMsg = array();
		try {
			if (isset($msg->{"bookName"}) && isset($msg->{"startBookId"}) && isset($msg->{"size"})) {
				$bookName = $msg->{"bookName"};
				$authorName;
				$startBookId = $msg->{"startBookId"};
				$size = $msg->{"size"};
		
				if (-1 == $startBookId)  {
					$returnMsg["returnCode"] = 1;
					$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
					mysql_select_db("taoshusysu_db", $con);
					mysql_query("set names 'utf8'");
		
					/*查询图书信息*/
					$sql = "SELECT * FROM bookinfo WHERE bookName LIKE '%$bookName%' ORDER BY time DESC, bookId DESC LIMIT $size ";
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
						
					
					/*获取startBookId对应的时间戳*/
					$time;
					$sql = "SELECT * FROM bookinfo WHERE bookid = '$startBookId'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_array($result))  {
						$time = $row["time"];
					}
					
					/*查询图书信息*/
					$sql = "SELECT * FROM bookinfo WHERE bookName LIKE '%$bookName%' AND bookId < $startBookId ORDER BY time DESC LIMIT $size";
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
	 * 根据标签搜索图书
	 * @param $msg
	 * @return $returnMsg 
	 * {"returnCode":0} ($msg中label、startBookId、size缺少，或者抛出异常)
	 * {"returnCode":1,"bookList":[{"bookId":"", "bookName":"","authorId":"","content":"","time":"","authorId":""},......]}
	 */
	public function getBooksByLabel($msg){
		$returnMsg = array();
		try {
			if (isset($msg->{"label"}) && isset($msg->{"startBookId"}) && isset($msg->{"size"})) {
				$label = $msg->{"label"};
				$startBookId = $msg->{"startBookId"};
				$size = $msg->{"size"};
		
				if (-1 == $startBookId)  {
					$returnMsg["returnCode"] = 1;
					$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
					mysql_select_db("taoshusysu_db", $con);
					mysql_query("set names 'utf8'");
		
					/*根据label获取图书信息*/
					$sql = "SELECT DISTINCT bookId FROM label WHERE content LIKE '%$label%' ORDER BY bookId DESC LIMIT $size";
					$result = mysql_query($sql);
					$tempList = array();
					while($row = mysql_fetch_array($result)) {
						$bookId = $row["bookId"];
						
						/*根据图书id获取图书信息*/
						$sql_2 = "SELECT * FROM bookinfo WHERE bookId = '$bookId'";
						$result_2 = mysql_query($sql_2);
						while ($row_2 = mysql_fetch_array($result_2)) {
							$tempMsg = array();
							$tempMsg["bookId"] = $row_2["bookId"];
							$tempMsg["bookName"] = $row_2["bookName"];
							$tempMsg["authorId"] = $row_2["authorId"];
							$authorId = $row_2["authorId"];
							$tempMsg["content"] = $row_2["content"];
							$tempMsg["time"] = $row_2["time"];
							
							/*获取作者的名字*/
							$sql_3 = "SELECT * FROM userinfo WHERE userId = '$authorId'";
							$result_3 = mysql_query($sql_3);
							if ($row_3 = mysql_fetch_array($result_3)) {
									$tempMsg["authorName"] = $row_3["userName"];
							}
							array_push($tempList, $tempMsg);
						}
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
		
					
					/*根据label获取图书信息*/
					$sql = "SELECT * FROM label WHERE CONTAINS(content,'$label') AND bookId < '$startBookId' ORDER BY bookId DESC LIMIT '$size'";
					$result = mysql_query($sql);
					$tempList = array();
					while($row = mysql_fetch_array($result)) {
						$bookId = $row["bookId"];
						
						/*根据图书id获取图书信息*/
						$sql_2 = "SELECT * FROM bookinfo WHERE bookId = '$bookId";
						$result_2 = mysql_query($sql_2);
						while ($row_2 = mysql_fetch_array($result_2)) {
							$tempMsg = array();
							$tempMsg["bookId"] = $row_2["bookId"];
							$tempMsg["bookName"] = $row_2["bookName"];
							$tempMsg["authorId"] = $row_2["authorId"];
							$authorId = $row_2["authorId"];
							$tempMsg["content"] = $row_2["content"];
							$tempMsg["time"] = $row_2["time"];
							
							/*获取作者的名字*/
							$sql_3 = "SELECT * FROM userinfo WHERE userId = '$authorId'";
							$result_3 = mysql_query($sql_3);
							if ($row_3 = mysql_fetch_array($result_3)) {
									$tempMsg["authorName"] = $row_3["userName"];
							}
							array_push($tempList, $tempMsg);
						}
					}
					$returnMsg["bookList"] = $tempList;
					mysql_close($con);
					return $returnMsg;
				}
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
	 * 删除图书
	 * @param $msg
	 * @return $returnMsg
	 * {"returnCode":1} (删除成功)
	 * {"returnCode":0} ($msg中bookId缺少，或者抛出异常)
	 */
	public function deleteBook($msg){
		$returnMsg = array();
		try {
			if (isset($msg->{"bookId"}))  {
				$bookId = $msg->{"bookId"};
		
				$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
				mysql_select_db("taoshusysu_db", $con);
				mysql_query("set names 'utf8'");
				
				
				/*删除该图书的有关评论*/
				mysql_query("DELETE FROM comment WHERE bookId = '$bookId'");
				/*删除该图书的标签*/
				mysql_query("DELETE FROM label WHERE bookId = '$bookId'");
				/*删除图书信息*/
				mysql_query("DELETE FROM bookinfo WHERE bookId = '$bookId'");
				
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
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
	}
}
?>