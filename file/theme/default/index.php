<?php
//Simple Message Manage
//简单信息管理

//2016.8.2
//By DTSDAO

//Default - DTSDAO
//Index

?>
<?php include "header.php"; ?>

			<h1 align=center><?php echo $this->objectName; ?></h1>
			<div align=center>

				<?php 
					if ($_SESSION[$this->func->getPre('username')] != "visitor") {
						?>
						<br />
						<a href="manage.php"><input type="submit" value="管理页面"></a>
						<?php
					} else {
						//登录
						?>
						<a href="login.php"><input type="submit" value="登录"></a>
						<?php
						if ($db->getConfig("allow_reg") == "on") {
							?>
							<br />
							<a href="register.php"><input type="submit" value="注册"></a>
							<?php
						}
					}
				?>
				
				<?php
					if (($db->getConfig("showpage") == "on") || (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"msg") !== false)) {
						?>
						<br />
						<br />
						<a href="show.php"><input type="submit" value="展示页"></a>
						<?php
					}
				?>
			</div>

<?php include "footer.php" ?>