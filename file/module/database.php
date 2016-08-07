<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Module - Database

class DB{
	public $conn;
	public $func;
	public $dbConf;	
	
	public function __construct($dbConf,$func){
		//new一个对象时打开数据库连接
		@$this->conn = new mysqli($dbConf['host'],$dbConf['user'],$dbConf['pwd'],$dbConf['db'],$dbConf['port']);
		
		//检查错误
		if (mysqli_connect_errno()){
			echo "<title>CONNECT_ERROR</title>\n";
			echo "<div align=center>" . mysqli_connect_errno($this->conn) . " " . mysqli_connect_error($this->conn) . "</div>";
			exit;
		} 
		
		//更新类属性
		$this->dbConf = $dbConf;
		$this->func = $func;
	}
	
	public function close(){
		//关闭数据库
		$this->conn->close();
	}
	
	public function checkError(){
		//检查错误
		if (mysqli_errno($this->conn)){
			echo "<title>DATABASE_ERROR</title>\n";
			echo "<div align=center>" . mysqli_errno($this->conn) . " " . mysqli_error($this->conn) . "</div>";
			exit;
		} 
	}
	
	public function checkPwd($username,$password){
		//检查密码
		$sql = $this->query("select * from " . $this->func->getPre("users") . " where username='" . $username . "' and password=md5('" . $password . "')");
		
		if ($sql->num_rows > 0) return true;
			else return false;
	}
	
	public function query($query){
		$sql = $this->conn->query($query);
		$this->checkError();
		
		return $sql;
	}
	
	public function getConfig($name){
		//获取设置
		$sql = $this->query("select value from " . $this->func->getPre("config") . " where name='" . $name . "'");
		
		return $sql->fetch_array()['value'];
	}
	
	public function getMsgRow(){
		return $this->query("select * from " . $this->func->getPre("msg"));
	}
	
	public function getUserRow(){
		return $this->query("select * from " . $this->func->getPre("users"));
	}
	
	public function getGroupRow(){
		return $this->query("select * from " . $this->func->getPre("group"));
	}
	
	public function getConfigRow(){
		return $this->query("select * from " . $this->func->getPre("config"));
	}
	
	public function getFormat(){
		//获取格式
		$i = 0;
		$format = array();
		$native = $this->getConfig("format");
		
		$token = strtok($native,",");
		while ($token !== false){
			$format[$i] = $token;
			$token = strtok(",");
			$i++;
		}
		
		return $format;
	}
	
	public function updateConfig($name,$value,$theme){
		//修改设置
		$sql = $this->query("update "  . $this->func->getPre("config") . " set value = '" . $value . "' where name = " . $name);
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function updateMsg($name,$value,$id,$theme){
		//修改条目
		$sql = $this->query("update "  . $this->func->getPre("msg") . " set " . $name . " = '" . $value . "' where id = " . $id);
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function createMsg($list,$theme){
		//创建条目
		$sql = $this->query("insert into " . $this->func->getPre("msg") . " values(" . $list . ")");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function deleteMsg($id,$theme){
		//删除条目
		$sql = $this->query("delete from " . $this->func->getPre("msg") . " where id = " . $id);
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function getUserGroup($user){
		//取得用户所在组
		$sql = $this->query("select * from " . $this->func->getPre("users") . " where username='" . $user . "'");
		if ($sql->num_rows == 0) return "visitor";
		
		$getGroup = $sql->fetch_assoc()['group'];
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $getGroup . "'");
		if (strpos($sql->fetch_assoc()['member'],$user) !== false) return $getGroup;
			else return "visitor";
	}
	
	public function updateUserGroup($user,$newGroup,$theme){
		//修改用户所在组
		
		//取值
		$selectUser = $addUser = $user;
		$oldGroup = $this->getUserGroup($user);
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $oldGroup . "'");
		$oldGroupMembers = $sql->fetch_assoc()['member'];
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $newGroup . "'");
		$newGroupMembers = $sql->fetch_assoc()['member'];
		
		//添加user
		if ($newGroupMembers != "") $addUser = "," . $user;
		$newMembers = $newGroupMembers . $addUser;
		
		//替换user
		if (strpos($oldGroupMembers,$user) !== 0) $selectUser = "," . $user;
		$oldMembers = str_replace($selectUser,"",$oldGroupMembers);
		
		//更新数据库
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member = '" . $oldMembers . "' where name = " . $oldGroup);
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
		
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member = '" . $newMembers . "' where name = " . $newGroup);
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
		
		$sql = $this->query("update "  . $this->func->getPre("users") . " set group = '" . $newGroup . "' where username = " . $user);
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function createGroup($group,$members = null,$permission = null,$theme){
		//创建组
		$sql = $this->query("insert into " . $this->func->getPre("group") . " values('" . $group . "','" . $members . "','" . $permission . "')");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function deleteGroup($group,$theme){
		//删除组
		$sql = $this->query("delete from " . $this->func->getPre("group") . " where name = " . $group);
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function getGroupPerm($group){
		//取得组权限
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $group . "'");
		return $sql->fetch_assoc()['permission'];
	}
	
	public function updateGroupPerm($group,$permission,$theme){
		//修改组权限
		$sql = $this->query("update "  . $this->func->getPre("group") . " set permission = '" . $permission . "' where name = " . $permission);
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function getUserPerm($user){
		//取得用户权限
		return $this->getGroupPerm($this->getUserGroup($user));
	}
	
	public function addUser($username,$password,$group,$theme){
		//添加用户
		
		//取得旧群组值
		$addUser = $username;
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $group . "'");
		$groupMembers = $sql->fetch_assoc()['member'];
		
		//添加user
		if ($groupMembers != "") $addUser = "," . $username;
		$newMembers = $groupMembers . $addUser;
		
		//更新数据库
		$sql = $this->query("insert into " . $this->func->getPre("users") . " values(null,'" . $username . "','" . md5($password) . "','" . $group . "')");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
		
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member = '" . $newMembers . "' where name = '" . $group . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function deleteUser($username,$theme){
		//删除组
		
		//取值
		$selectUser = $user;
		$oldGroup = $this->getUserGroup($user);
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $oldGroup . "'");
		$oldGroupMembers = $sql->fetch_assoc()['member'];
		
		//替换user
		if (strpos($oldGroupMembers,$user) !== 0) $selectUser = "," . $user;
		$oldMembers = str_replace($selectUser,"",$oldGroupMembers);
		
		//更新数据库
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member = '" . $oldMembers . "' where name = " . $oldGroup);
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
		
		$sql = $this->query("delete from " . $this->func->getPre("users") . " where username = " . $username);
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
	
	public function updateUserPassword($username,$newPassword,$theme){
		//修改密码
		$sql = $this->query("update "  . $this->func->getPre("users") . " set password = '" . md5($newPassword) . "' where username = " . $username);
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("出了点问题，请检查数据库！");
	}
}
?>