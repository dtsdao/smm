<?php
//Simple Message Manage
//简单信息管理

//2016.8.2
//By DTSDAO
//Theme - Load

class THEME{
	private $objectName;
	private $db;
	private $themeUrl;
	private $title;
	private $func;
	
	public function __construct($pagename,$db,$title = false){
		//获取设置
		$theme = $db->getConfig("theme");
		
		//设置类属性
		$this->objectName = $db->getConfig("object_name");
		$this->db = $db;
		$this->themeUrl = 'file/theme/' . $theme . '/';
		$this->func = $db->func;
		if (!$title) $this->title = strtoupper($pagename);
			else $this->title = strtoupper($title);
		
		
		//判断主体是否存在
		if (!is_dir($this->themeUrl) || !file_exists($this->themeUrl . 'header.php') || !file_exists($this->themeUrl . 'footer.php')){
			$this->divAgc("缺少主题或基本页面，请修复！");
			exit;
		}
		if (!file_exists($this->themeUrl . $pagename . '.php')){
			$this->divAgc("缺少" . $pagename . "页面！");
			exit;
		}
		
		include $this->themeUrl . $pagename . '.php';
	}
	
	public function divAgc($inner){
		//较为美观（？）的信息提示
		echo "\n<div align=center>\n" . $inner . "\n</div>";
	}
}
?>