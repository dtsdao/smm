<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO
//Install

if (file_exists("file/config.php")){
	header("Location: index.php");
	exit;
} 

ini_set('error_reporting','E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE');

if (!$_POST['step']) $title = "INDEX"; else $title = strtoupper($_POST['step']);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8>
		<title><?php echo $title; ?> - SMM_INSTALL</title>
		<style type="text/css">
			body {
				background-color:#FFF;
				color:#444;
				font-family:Times New Roman;
				font-size:87.5%;
			}
			a {
				color:#3354AA;
				text-decoration:none;
			}
			h1,h2,h3,h4,h5,h6 {
				font-size:250%;
				font-family:Microsoft Yahei;
			}
			table {
				text-align: center;
			}
			#footer {
				padding:3em 0;
				line-height:1.5;
				text-align:center;
				color:#999;
			}
			.text {
				color:#999;
			}
		</style>
	</head>
	
	<body>
		<div>
			<?php
			if (($_POST['step'] == "config") || ($_POST['step'] == "finish")){
				include "file/essentials.php";
			}

			switch($_POST['step']){
				case 'database':
					?>
						<h1 align=center>数据库设置</h1>
						<div align=center>
						<form action="install.php" method="POST">
							<input type="hidden" name="step" value="user">
							地址:<input name="host"><br />
								<div class="text">暂仅支持MySQL 5.x及MariaDB 10.x</div><br />
							用户名:<input name="user"><br />
								<div class="text">为保证安全，请单独创建一个用户，并只给予下面数据库的所有权限（虚拟主机请当这句话放屁）</div><br />
							密码:<input name="pwd"><br />
							数据库名称:<input name="db"><br />
								<div class="text">自己搭建的数据库服务器请确保已创建该数据库（虚拟主机同上）</div><br />
							端口:<input name="port" value="3306"><br />
							前缀:<input name="prefix" value="smm_"><br />
								<div class="text">请保持良好习惯！虽然你填个123什么乱七八糟且不加下划线也能识别，但数据库会很乱</div><br />
							<input type="submit" value="保存">
						</form>
						</div>
					<?php
					break;
				case 'user':
					$newConf = array(
						'host' => $_POST['host'],
						'user' => $_POST['user'],
						'pwd' => $_POST['pwd'],
						'db' => $_POST['db'],
						'port' => $_POST['port'],
						'prefix' => $_POST['prefix']
					);
					$conn = new mysqli($newConf['host'],$newConf['user'],$newConf['pwd'],$newConf['db'],$newConf['port']);
					if (!$conn){
						echo "连接错误！请重新安装";
						break;
					}
					$file = fopen("file/config.php",'w');
					fwrite($file,
"<?php
//Simple Message Manage
//简单信息管理

//Config

\$dbConf = array(
	'host' => '" . $newConf['host'] . "',
	'user' => '" . $newConf['user'] . "',
	'pwd' => '" . $newConf['pwd'] . "',
	'db' => '" . $newConf['db'] . "',
	'port' => " . $newConf['port'] . ",
	'prefix' => '" . $newConf['prefix'] . "'
);
?>");
					fclose($file);
					
					$conn->query("CREATE TABLE `" . $_POST['prefix'] . "config" . "` (`name` varchar(100) NOT NULL,`value` varchar(500) NOT NULL,PRIMARY KEY (`name`))");
					$conn->query("CREATE TABLE `" . $_POST['prefix'] . "group" . "` (`name` varchar(64) NOT NULL,`member` varchar(64) DEFAULT NULL,`permission` varchar(500) DEFAULT NULL,PRIMARY KEY (`name`))");
					$conn->query("CREATE TABLE `" . $_POST['prefix'] . "users" . "` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT,`username` varchar(64) NOT NULL,`password` varchar(40) NOT NULL,`groupname` varchar(64) NOT NULL,PRIMARY KEY (`id`,`username`))");
					
					$conn->close();
					?>
						<h1 align=center>创建管理员</h1>
						<div align=center>
						<form action="install.php" method="POST">
							<input type="hidden" name="step" value="config">
							<div class="text">此用户将作为本系统的初始管理员，隶属admin组</div><br />
							用户名:<input name="username"><br />
							密码:<input type="password" name="password"><br />
							<input type="submit" value="保存">
						</form>
						</div>
					<?php
					break;
				case 'config':
					$db->query("insert into " . $func->getPre("users") . " values(null,'" . $_POST['username'] . "','" . $func->mix($_POST['password']) . "','admin')");
					$db->query("insert into " . $func->getPre("group") . " values('admin','" . $_POST['username'] . "','msg,users,config')");
					$db->query("insert into " . $func->getPre("group") . " values('visitor','visitor',null)");
					$_SESSION[$func->getPre('username')] = $_POST['username'];
					?>
						<h1 align=center>设置</h1>
						<div align=center>
						<form action="install.php" method="POST">
							<input type="hidden" name="step" value="finish">
							theme:<input name="theme" value="default"><br />
								<div class="text">与主题目录相同</div><br />
							showpage:<input name="showpage" value="off"><br />
								<div class="text">是否免登陆展示信息（on/off）</div><br />
							allow_reg:<input name="allow_reg" value="off"><br />
								<div class="text">是否允许注册（on/off）</div><br />
							object_name:<input name="object_name"><br />
								<div class="text">项目名称</div><br />
							format:<input name="format" value="msg1,msg2"><br />
								<div class="text">格式</div><br />
							default_group:<input name="default_group" value="visitor"><br />
								<div class="text">注册后默认的组</div><br />
							personal:<input name="personal" value="off"><br />
								<div class="text">是否开启单用户单表(on/off)</div><br />
							<input type="submit" value="保存">
						</form>
						</div>
					<?php
					break;
				case 'finish':
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('theme', '" . $_POST['theme'] . "')");
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('showpage', '" . $_POST['showpage'] . "')");
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('allow_reg', '" . $_POST['allow_reg'] . "')");
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('object_name', '" . $_POST['object_name'] . "')");
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('format', '" . $_POST['format'] . "')");
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('default_group', '" . $_POST['default_group'] . "')");
					$db->query("INSERT INTO `" . $func->getPre("config") . "` (`name`, `value`) VALUES ('personal', '" . $_POST['personal'] . "')");
					
					if ($db->getConfig('personal') == "on") $db->createMsgTable($_SESSION[$func->getPre('username')]);
					$db->createMsgTable("system");
					?>
						<h1 align=center>完成</h1>
						<div align=center>
						恭喜，您已成功完成安装，可以删除本文件_(:з」∠)_<br />
						<form action="index.php" method="GET">
							<input type="submit" value="完成">
						</form>
						</div>
					<?php
					break;
				case 'index':
				default:
					?>
						<h1 align=center>安装</h1>
						<div align=center>
							<div align=center style="color: red">
							警告：<br />
							请严格按照文本框旁边的说明来填写<br />
							由于自行乱填导致的任何后果概不负责<br />
							错误代码及错误信息请自行百度，不要随便发issue<br />
							出现错误后请务必删除file目录下config.php及删除所选数据库中生成的表<br />
							感谢安装本软件<br />
							2016.8.7<br />
							DTSDAO
							</div>
							<br />
						<form action="install.php" method="POST">
							<input type="hidden" name="step" value="database">
							<input type="submit" value="下一步">
						</form>
						</div>
					<?php
					break;
			}
			if (($_POST['step'] == "config") || ($_POST['step'] == "finish")){
				$db->close();
			}
			
			?>
			<div align=center id="footer">
				SMM INSTALL
			</div>
	
		</div>
	</body>
</html>