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
			echo "<div align=center>" . mysqli_connect_errno($this->conn) . mysqli_connect_error($this->conn) . "</div>";
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
			echo "<div align=center>" . mysqli_errno($this->conn) . mysqli_error($this->conn) . "</div>";
			exit;
		} 
	}
	
	public function checkPwd($username,$password){
		//检查密码
		$sql = $this->conn->query("select * from " . $this->func->getPre("users") . " where username='" . $username . "' and password=md5('" . $password . "')");
		$this->checkError();
		
		if ($sql->fetch_array()) return true;
			else return false;
	}
	
	public function getConfig($name){
		//获取设置
		$sql = $this->conn->query("select value from " . $this->func->getPre("config") . " where name='" . $name . "'");
		$this->checkError();
		
		return $sql->fetch_array()['value'];
	}
}
?>