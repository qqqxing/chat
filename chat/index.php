<?php

if(isset($_GET['op'])){
	$op = $_GET['op'];
	if ($op == "quit"){
		setcookie('userid', NULL);
	}
}

if (isset($_COOKIE['userid'])){
	header("location:chat.php?op=directlogin");
} else {
	$flag = false;
	if (isset($_GET['op'])){
		$op = $_GET['op'];
		if($op == "login"){
			if (isset($_GET['username']) && $_GET['username'] != ""){
				$login_name = $_GET['username'];
				$cookie_time = 3600 * 24 * 365;
				setcookie('userid',$login_name,time() + $cookie_time );
				header("location:chat.php?op=directlogin");
			} else {
				die("用户名为空.");
			}
		}

	} else {
		?>



<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>登录聊天</title>
</head>
<body>

<div id="main">
<div id="loginform">
<form action="index.php" method="GET"><input type="hidden" name="op"
	value="login"> 用户名：<input type="text" name="username" /> <input
	type="submit" value="登录" /></form>
<br />
<span></span></div>
</div>

</body>
</html>


		<?php
	}
}
?>
