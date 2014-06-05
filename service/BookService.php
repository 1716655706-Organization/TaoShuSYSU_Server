<?php

include_once 'Service.php';

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
	/**
	 * 构造函数，在这里注册相应命令
	 */
	public function UserService() {
		$this->register(self::$ADDBOOK_ID, "addBookInfo");
		$this->register(self::$GETBOOKSINFO_ID, "getBooksInfo");
	}
	
	/**
	 * 用户注册
	 * @param $msg
	 */
	public function addBookInfo($msg) {
		$returnMsg = array();
		if (isset($msg->{"bookName"}))  {
			$bookName = $msg->{"bookName"};
			$authorId = $msg->{"userId"};
			$labelArr = $msg->{"labelArr"};
			$content = $msg->{"content"};
			$currentTime = time();
			
			$con = mysql_connect("localhost", "root", "");
			mysql_select_db("taoshusysu_db", $con);
			
			/*插入存在*/
			mysql_query("INSERT INTO userinfo (bookName,authorId,content)
				VAlUES ('$bookName', '$authorId','$content')", $con);
			
			/*查询刚插入图书信息的id*/
			$sql = mysql_query("SEARCH * FROM bookinfo WHERE userName = $userName");
			$row = mysql_fetch_array($sql);
			while ($rs = mysql_fetch_array($row)) {
				if ($userName == $rs["userName"]) {
					$returnMsg["returnCode"] = -1;
					return $returnMsg;
				}
			}
			/*插入数据*/
			mysql_query("INSERT INTO userinfo (userName, password) 
						VAlUES ('$userName', '$password')", $con);
			
			/*获取id*/
			$sql = mysql_query("SEARCH * FROM userinfo WHERE userName = $userName");
			$row = mysql_fetch_array($sql);
			mysql_close($con);
			while ($rs = mysql_fetch_array($row)) {
				$returnMsg["userId"] = intval($rs["userId"]);
				$returnMsg["returnCode"] = 1;
				return $returnMsg;
			}
		}
		else {
			$returnMsg["returnCode"] = 0;
			return $returnMsg;
		}
	}
	
	public function handle_login($msg){
		$returnMsg = array();
		if (isset($msg->{"userName"}) && isset($msg->{"password"})) {
			$userName = $msg->{"userName"};
			$password = $msg->{"password"};
			$con = mysql_connect("localhost", "root", "");
			mysql_select_db("taoshusysu_db", $con);
			/*是否存在*/
			$sql = mysql_query("SEARCH * FROM userinfo WHERE userName = $userName");
			$row = mysql_fetch_array($sql);
			while ($rs = mysql_fetch_array($row)) {
				if ($userName == $rs["userName"] && $password == $rs["password"]) {
					$returnMsg["returnCode"] = 1;
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