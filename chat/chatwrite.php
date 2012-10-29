<?php

require_once 'config.php';

class WriteResult{
	public $error;
}

if ( !isset($_COOKIE['userid']) || $_COOKIE['userid'] == ""){
	$out = new WriteResult();
	$out->error = 1;
	print json_encode($out);
	die();
}


if (isset($_GET['content']) && $_GET['content'] != ""){



	$content = $_GET['content'];
	$user = $_COOKIE['userid'];
	$datetime = date("Y-m-d H:i:s");

	$mysqli = new mysqli($db_host,$db_user,$db_pw,$db_name);

	$out = new WriteResult();


	if (!$mysqli){
		$out->error = 1;
		print json_encode($out);
		die();
	}

	$query = "insert into chatlogs(user,content,datetime) values(?,?,?)";


	$bind_result = $mysqli->prepare($query);
	$bind_result->bind_param("sss",$user,$content,$datetime);
	$bind_result->execute();
	$bind_result->close();
	$mysqli->close();

	$out->error = 0;
	print json_encode($out);

}else{
	$out = new WriteResult();
	$out->error = 2;
	print json_encode($out);
	die();
}





?>