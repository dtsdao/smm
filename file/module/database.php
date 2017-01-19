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
		$sql = $this->query("select * from " . $this->func->getPre("users") . " where username='" . $username . "' and password='" . $this->func->mix($password) . "'");
		
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
		$getResult = $sql->fetch_assoc();
		return $getResult['value'];
	}
	
	public function getMsgRow(){
		return $this->query("select * from " . $this->getMsgTable());
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
		$sql = $this->query("update "  . $this->func->getPre("config") . " set value = '" . $value . "' where name='" . $name . "'");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("设置值一致或数据库出错！");
	}
	
	public function updateMsg($name,$value,$id,$theme){
		//修改条目
		$sql = $this->query("update "  . $this->getMsgTable() . " set " . $name . " = '" . $value . "' where id='" . $id . "'");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("未成功修改序号为" . $id . "的" . $name . "项！");
	}
	
	public function createMsg($list,$theme){
		//创建条目
		$sql = $this->query("insert into " . $this->getMsgTable() . " values(" . $list . ")");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法创建条目！");
	}
	
	public function deleteMsg($id,$theme){
		//删除条目
		$sql = $this->query("delete from " . $this->getMsgTable() . " where id='" . $id . "'");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法删除条目！");
	}
	
	public function getUserGroup($user){
		//取得用户所在组
		$sql = $this->query("select * from " . $this->func->getPre("users") . " where username='" . $user . "'");
		if ($sql->num_rows == 0) return "visitor";
		
		$getResult = $sql->fetch_assoc();
		$getGroup = $getResult['groupname'];
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $getGroup . "'");
		$getResult = $sql->fetch_assoc();
		if (strpos($getResult['member'],$user) !== false) return $getGroup;
			else return "visitor";
	}
	
	public function updateUserGroup($user,$newGroup,$theme){
		//修改用户所在组
		
		//取值
		$selectUser = $addUser = $user;
		$oldGroup = $this->getUserGroup($user);
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $oldGroup . "'");
		$getResult = $sql->fetch_assoc();
		$oldGroupMembers = $getResult['member'];
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $newGroup . "'");
		$getResult = $sql->fetch_assoc();
		$newGroupMembers = $getResult['member'];
		
		//添加user
		if ($newGroupMembers != "") $addUser = "," . $user;
		$newMembers = $newGroupMembers . $addUser;
		
		//替换user
		if (strpos($oldGroupMembers,$user) !== 0) $selectUser = "," . $user; else $selectUser = $user . ",";
		$oldMembers = str_replace($selectUser,"",$oldGroupMembers);
		
		//更新数据库
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member='" . $oldMembers . "' where name='" . $oldGroup . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法更新原来组数据！数据是否一致？");
		
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member='" . $newMembers . "' where name='" . $newGroup . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法更新新组数据！数据是否一致？");
		
		$sql = $this->query("update "  . $this->func->getPre("users") . " set groupname='" . $newGroup . "' where username='" . $user . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法更新用户表数据！");
	}
	
	public function createGroup($group,$members = null,$permission = null,$theme){
		//创建组
		$sql = $this->query("insert into " . $this->func->getPre("group") . " values('" . $group . "','" . $members . "','" . $permission . "')");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法创建组！");
	}
	
	public function deleteGroup($group,$theme){
		//删除组
		$sql = $this->query("delete from " . $this->func->getPre("group") . " where name='" . $group . "'");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法删除组！");
	}
	
	public function getGroupPerm($group){
		//取得组权限
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $group . "'");
		$getResult = $sql->fetch_assoc();
		return $getResult['permission'];
	}
	
	public function updateGroupPerm($group,$permission,$theme){
		//修改组权限
		$sql = $this->query("update "  . $this->func->getPre("group") . " set permission='" . $permission . "' where name='" . $group . "'");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("权限一致或数据库出错！");
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
		$getResult = $sql->fetch_assoc();
		$groupMembers = $getResult['member'];
		
		//添加user
		if ($groupMembers != "") $addUser = "," . $username;
		$newMembers = $groupMembers . $addUser;
		
		//更新数据库
		if ($this->getConfig('personal') == "on") $this->createMsgTable($username);
		
		$sql = $this->query("insert into " . $this->func->getPre("users") . " values(null,'" . $username . "','" . $this->func->mix($password) . "','" . $group . "')");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法创建用户！");
		
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member='" . $newMembers . "' where name='" . $group . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法更新组数据！");
	}
	
	public function deleteUser($username,$theme){
		//删除用户
		
		//取值
		$selectUser = $username;
		$oldGroup = $this->getUserGroup($username);
		
		$sql = $this->query("select * from " . $this->func->getPre("group") . " where name='" . $oldGroup . "'");
		$getResult = $sql->fetch_assoc();
		$oldGroupMembers = $getResult['member'];
		
		//替换user
		if (strpos($oldGroupMembers,$username) !== 0) $selectUser = "," . $username; else $selectUser = $username . ",";
		$oldMembers = str_replace($selectUser,"",$oldGroupMembers);
		
		//更新数据库
		$sql = $this->query("update "  . $this->func->getPre("group") . " set member = '" . $oldMembers . "' where name='" . $oldGroup . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法更新组数据！");
		
		$sql = $this->query("delete from " . $this->func->getPre("users") . " where username='" . $username . "'");
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("无法删除用户！");
	}
	
	public function updateUserPassword($username,$newPassword,$theme){
		//修改密码
		$sql = $this->query("update "  . $this->func->getPre("users") . " set password = '" . $this->func->mix($newPassword) . "' where username='" . $username . "'");
		
		if ((!$sql) || ($this->conn->affected_rows < 1)) $theme->divAgc("未成功修改用户" . $username . "的密码");
	}
	
	public function createMsgTable($username){
		//创建信息表
		$format = $this->getFormat();
		
		foreach ($format as $sign){
			$list .= ",`" . $sign . "` varchar(500) DEFAULT NULL";
		}
		
		$sql = $this->query("CREATE TABLE `" . $this->func->getPre("msg_" . $username) . "` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT" . $list . ",`author` varchar(64) NOT NULL,`time` date NOT NULL,PRIMARY KEY (`id`))");
		if (!$sql) echo "<div align=center>" . "未成功创建用户" . $username . "的信息表" . "</div>";
	}
	
	private function getMsgTable(){
		if ($this->getConfig('personal') == "on") return $this->func->getPre("msg_" . $_SESSION[$this->func->getPre('username')]);
			else return $this->func->getPre("msg_system");
	}
}
?>