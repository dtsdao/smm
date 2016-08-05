<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Module - Database

class DB{
	public $conn;
	private $dbConf;
	private $func;
	
	public function __construct($dbConf,$func){
		//new一个对象时打开数据库连接
		$this->conn = new mysqli($dbConf['host'],$dbConf['user'],$dbConf['pwd'],$dbConf['db'],$dbConf['port']);
		$this->checkError();
		
		$this->dbConf = $dbConf;
		$this->func = $func;
	}
	
	public function close(){
		$this->conn->close();
	}
	
	public function checkError(){
		//查看是否有错误
		if (mysqli_connect_errno($this->conn)){
			echo mysqli_connect_errno($this->conn) . mysqli_connect_error($this->conn);
			exit;
		} 
		if (mysqli_errno($this->conn)){
			echo mysqli_errno($this->conn) . mysqli_error($this->conn);
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