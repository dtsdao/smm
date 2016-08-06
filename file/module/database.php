<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Module - Database

class DB{
	public $conn;
	private $dbConf;
	public $func;
	
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
	
	private function query($query){
		$sql = $this->conn->query($query);
		$this->checkError();
		
		return $sql;
	}
	
	public function getConfig($name){
		//获取设置
		$sql = $this->query("select value from " . $this->func->getPre("config") . " where name='" . $name . "'");
		
		return $sql->fetch_array()['value'];
	}
	
	public function getMsg(){
		return $this->query("select * from " . $this->func->getPre("msg"));
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
	
	public function updateMsg($name,$value,$id){
		//修改条目
		$sql = $this->query("update "  . $this->func->getPre("msg") . " set " . $name . " = '" . $value . "' where id = " . $id);
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
}
?>