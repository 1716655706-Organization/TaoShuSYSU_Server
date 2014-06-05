<?php

include_once 'Service.php';

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
		if (isset($msg->{"userName"}) && isset($msg->{"password"})) {
			$userName = $msg->{"userName"};
			$password = $msg->{"password"};
			$con = mysql_connect("localhost", "root", "");
			mysql_select_db("taoshusysu_db", $con);
			/*是否已存在*/
			$sql = mysql_query("SEARCH * FROM userinfo WHERE userName = $userName");
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