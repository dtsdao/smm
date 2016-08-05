<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Functions

class functions{
	private $dbConf;
	
	public function __construct($dbConf){
		$this->dbConf = $dbConf;
	}
	
	//跳转页面
	public function turn($page){
		echo '<script>window.location.href="' . $page . '"</script>';
	}

	//返回带前缀的名称
	public function getPre($name){
		return $this->dbConf['prefix'] . $name;
	}
}
?>