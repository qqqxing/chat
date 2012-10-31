<?php


$cookie_time = 3600 * 24 * 365;

$remoteip = $_SERVER['REMOTE_ADDR'];


if ( !isset($_COOKIE['userid']) || $_COOKIE['userid'] == ""){
	header("location:index.php");
}

$userid = $_COOKIE['userid'];

$lastindex = 0;
if(isset($_COOKIE['lastindex']) && $_COOKIE['lastindex'] != ""){
	$lastindex = $_COOKIE['lastindex'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>chat</title>

<script type="text/javascript"
	src="http://code.jquery.com/jquery-1.8.2.js"></script>

<script type="text/javascript">


var lastindex = <?php echo $lastindex?>;
var username = "<?php echo $userid ?>";

var loading = false;

var newmsg = false;
var show_count = 0;
var show_flag = 0;
var title_default = "";

function change_title(){
	if(newmsg == true){
		if(show_flag == 0){
			$("title").text("新消息 " + title_default);
			show_flag == 1;
		}else {
			$("title").text(title_default);
			show_flag == 0;
		}
		show_count ++;
		if(show_count > 4){
			show_count = 0;
			newmsg = false;
		}
	}
}

function request_msg(){
	var new_msg_coming = false;
	var url = "chatread.php?lastindex=" + lastindex;
	$.getJSON(url,function(data){
		var result = "";
		$.each(data.records,function(InfoIndex,info){
			result += info["datetime"] + "\n";
			result += info["user"] + ":" + info["content"];
			result += "\n\n";
			if( username != info["user"] ){
				new_msg_coming = true;
			}
		});
		if(result != ""){
			$("#chatwindow").text($("#chatwindow").text() + result);
			if(new_msg_coming == true){
				$("title").text("新消息 " + title_default);
			}
		}
		ajustChatWindowsScroll();
		lastindex = data.lastindex;
		loading = false;
	});
}


function loadmsg(){
	if(loading == false){
		loading = true;
		request_msg();
	}
}





$(function(){
		loading = true;
		var url = "chatread.php?lastindex=" + lastindex;
		$.getJSON(url,function(data){
			
			var result = "";
			$.each(data.records,function(InfoIndex,info){
				result += info["datetime"] + "\n";
				result += info["user"] + ":" + info["content"];
				result += "\n\n";
			});
			$("#chatwindow").empty();
			$("#chatwindow").text(result);
			ajustChatWindowsScroll();
			lastindex = data.lastindex;
			loading = false;
		});

		setInterval("loadmsg()",2000);
		title_default = $("title").text();
});


function send(){
	var content = $("#content").val();
	if(content == ""){
		$("#sendresult").text("发送内容为空");
	}else{
		var url = "chatwrite.php?content=" + content;
		$.getJSON(url,function(data){
			var error = data.error;
			if(error == 0){
				$("#content").val("");
			}else{
				$("#sendresult").text("发送失败，请刷新页面");
			}
		});
	}
}

$(function(){
	$("#enter").click(function(){
		send();
	});
});

$(function(){
	$("#quit").click(function(){
		location.href = "index.php?op=quit";
	});
});

$(function(){
	$("#content").bind('keyup',function(event){
		if(event.keyCode == '13'){
			send();
		}
	});

	$("#content").bind('focus',function(event){
		$("title").text(title_default);
	});
});



function ajustChatWindowsScroll(){
	var obj = document.getElementById("chatwindow");
     obj.scrollTop = obj.scrollHeight;
}


</script>

</head>
<body>

<div id="main">
<div style="width: 800px; height: 50px"><span>IP : <?php echo $remoteip ?></span>
<br />
<span>用户名 : <?php echo $userid ?></span> <br />
</div>
<div style="width: 800px; height: 500px"><textarea id="chatwindow"
	rows="20" cols="50"></textarea> <br />

<textarea rows="3" cols="50" id="content">

</textarea> <br />

<input type="button" name="enter" id="enter" value="提交" /> <input
	type="button" name="quit" id="quit" value="退出聊天" /> <br />
<span id="sendresult"> </span></div>
</div>
</body>
</html>
