<?php

include_once 'Service.php';

header("content-type:text/html; charset=utf-8");

class UserService extends Service{
	/**
	 * 用于注册Service的Id
	 */
	public static $SERVICE_ID = 0;
	
	/**
	 * 用户注册命令的Id
	 */
	private static $REGISTER_ID = 0;
	private static $LOGIN_ID = 1;
	/**
	 * 构造函数，在这里注册相应命令
	 */
	public function UserService() {
		$this->register(self::$REGISTER_ID, "handle_register");
		$this->register(self::$LOGIN_ID, "handle_login");
	}
	
	/**
	 * 用户注册
	 * @param $msg
	 */
	public function handle_register($msg) {
		$returnMsg = array();
try {	
		if (isset($msg->{"userName"}) && isset($msg->{"password"})) {
			$userName = $msg->{"userName"};
			$password = $msg->{"password"};
			
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
			
			$sql = "SELECT * FROM userinfo WHERE userName = '$userName'";
			$result = mysql_query($sql);
			
			if (mysql_fetch_array($result)) {
				$returnMsg["returnCode"] = -1;
				return $returnMsg;
			}
			
			/*插入数据*/
			mysql_query("INSERT INTO userinfo (userName, password)
				VAlUES ('$userName', '$password')", $con);
			
			/*获取用户id*/
			$userId = mysql_insert_id();
			
			mysql_close($con);
			$returnMsg["returnCode"] = 1;
			$returnMsg["userId"] = $userId;			
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
	 * 处理登录
	 * @param  $msg
	 * @return multitype:number
	 */
	public function handle_login($msg){
		$returnMsg = array();
try {
		if (isset($msg->{"userName"}) && isset($msg->{"password"})) {
			$userName = $msg->{"userName"};
			$password = $msg->{"password"};
			$con = mysql_connect(DatabaseConstant::$MYSQL_HOST, DatabaseConstant::$MYSQL_USERNAME, DatabaseConstant::$MYSQL_PASSWORD);
			mysql_select_db("taoshusysu_db", $con);
			mysql_query("set names 'utf8'");
			
			/*是否存在*/
			$sql ="SELECT * FROM userinfo WHERE userName = '$userName'";
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
catch (Exception $e) {
		$returnMsg = array();
		$returnMsg["returnCode"] = 0;
		echo $returnMsg;
}
	}
}
?>