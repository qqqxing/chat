<?php

require_once 'config.php';

class Record{
	public $index;
	public $user;
	public $content;
	public $datetime;
}

class Result{
	public $error;
	public $lastindex;
	public $count;
	public $records;

	function __construct(){
		$this->records = array();
	}
}

if ( !isset($_COOKIE['userid']) || $_COOKIE['userid'] == ""){
	$out = new Result();
	$out->error = 1;
	print json_encode($out);
	die();
}




if (!isset($_GET['lastindex']) || $_GET['lastindex'] == "") {
	//read last 20 records
	$out = new Result();
	$dbcon = mysql_connect($db_host,$db_user,$db_pw);
	if (!$dbcon){
		$out->error = 2;
		print json_encode($out);
		die();
	}
	mysql_select_db($db_name);
	$query = "select * from (select * from chatlogs ORDER BY id DESC limit 20)T order by id";
	$result = mysql_query($query);

	$temp_last_index = 0;
	$count = 0;

	while($row = mysql_fetch_array($result)){
		$id = $row['id'];
		$user = $row['user'];
		$content = $row['content'];
		$datetime = $row['datetime'];

		$temp_record = new Record();
		$temp_record->index = $id;
		$temp_record->user = $user;
		$temp_record->content = $content;
		$temp_record->datetime = $datetime;

		array_push($out->records, $temp_record);

		if ($temp_last_index < $id){
			$temp_last_index = $id;
		}

		$count ++;
	}

	$out->lastindex = $temp_last_index;
	$out->count = $count;

	mysql_close();
	$out->error = 0;
	print json_encode($out);
} else {
	$lastindex = $_GET['lastindex'];
	$out = new Result();
	$dbcon = mysql_connect($db_host,$db_user,$db_pw);
	if (!$dbcon){
		$out->error = 2;
		print json_encode($out);
		die();
	}


	mysql_select_db($db_name);
	$query = "select * from (select * from chatlogs where id>$lastindex ORDER BY id DESC limit 20)T order by id";
	$result = mysql_query($query);

	$temp_last_index = $lastindex;
	$count = 0;

	while($row = mysql_fetch_array($result)){
		$id = $row['id'];
		$user = $row['user'];
		$content = $row['content'];
		$datetime = $row['datetime'];

		$temp_record = new Record();
		$temp_record->index = $id;
		$temp_record->user = $user;
		$temp_record->content = $content;
		$temp_record->datetime = $datetime;

		array_push($out->records, $temp_record);

		if ($temp_last_index < $id){
			$temp_last_index = $id;
		}

		$count ++;
	}

	$out->lastindex = $temp_last_index;
	$out->count = $count;

	mysql_close();
	$out->error = 0;
	print json_encode($out);
}

?>