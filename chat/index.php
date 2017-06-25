<?php
session_start();
require '../elements/conn.php';

if(isset($_COOKIE['username'])) {
	$logged = $_COOKIE['username'];
	$logged = mysqli_real_escape_string($conn, $logged);
	$logged = htmlentities($logged, ENT_QUOTES);
}
else {
	$logged = '';
}
if(isset($_COOKIE['password'])) {
	$password = $_COOKIE['password'];
	$password = mysqli_real_escape_string($conn, $password);
	$password = htmlentities($password, ENT_QUOTES);
}
else {
	$password = '';
}
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,points,kicked FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location: /logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$points = $row['points'];
$kicked = $row['kicked'];
include('room.php');
$ccount = 0;
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
mysqli_query($conn, "UPDATE members SET ".$roomol."=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}

$sess_set = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SomeSN - Chat <?php echo $roomname; ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="google" value="notranslate">
<meta name="theme-color" content="#4c43fa">
<link rel="icon" sizes="192x192" href="/images/somesn.png">
<meta name="mobile-web-app-capable" content="yes">
<link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>
<div id="title"><a href="/chat/roomsel.php"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Chat</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<div class="olistc">
Chat: <?php echo $roomname; ?> - <span id="circleol" class="cb1">Rooms</span> <span id="circleol" class="cb2">Online list
</span>&nbsp;&nbsp;<span class="status"><span class="chatstatus" style="background-color:#2c5fff;">&nbsp;</span></span></div>
<div class="list1">
<div style="line-height:5px;">&nbsp;</div>
<div class="tab">Rooms</div>
<div style="line-height:5px;">&nbsp;</div>
<div id="roombar" onclick="location.href='/chat/?room=orig'">Original</div>
<div style="line-height:5px;">&nbsp;</div>
<div id="roombar" onclick="location.href='/chat/?room=rp'">Roleplay</div>
<?php
if($admin < 4) {
	echo '<div style="line-height:5px;">&nbsp;</div>';
	echo '<div id="roombar" onclick="location.href=\'/chat/?room=staff\'">Staff</div>';
}
?>
<div style="line-height:5px;">&nbsp;</div>
<div id="roombar" onclick="location.href='/chat/?room=dev'">Developers</div>
</div>
<div class="list2">
<div style="line-height:5px;">&nbsp;</div>
<div class="tab">Online List</div>
<div class="list2c">
<div id="onlinescreen">
<?php include 'online.php'; ?>
</div>
</div>
</div>
<div id="chatscreen">
<div style="text-align:center;">
<div class="loader">
<img src="/images/loading.gif" width="30px" height="30px" alt="Loading...">
<br>
Loading...
</div>
</div>
</div>
<div class="olistclose">&nbsp;</div>
<div id="textboxmsg">
<form class="sendbutton" method="post" autocomplete="off">
<div id="econ">
<input id="msg" name="msg" type="text" value="" class="textfield" maxlength="1000" placeholder="Type here..." style="width:80%;display:inline-block;">
<input type="submit" class="sendbuttonaction" id="sendbuttonaction" value="Send">
</div>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</form>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script language="javascript" type="text/javascript">
firstloader = 0;

function updateChat() {
	$.get('chatmsg.php?room=<?php echo $getroom; ?>', function(results) {
		if(results != '') {
		$('#chatscreen').html(results);
		if(firstloader == 0) {
			$('#chatscreen').scrollTop($('#chatscreen').prop("scrollHeight"));
			firstloader = 1;
		}
	}
	});
}

// Update if focused START
var interval_delay = 5000;
var myInterval = setInterval('updateChat()', interval_delay);
var is_interval_running = false;

$(document).ready(function () {
	$('.status').html('<span class="chatstatus" style="background-color:#14e800;">&nbsp;</span>');
	$(window).focus(function () {
		clearInterval(myInterval);
		if(!is_interval_running)
			updateChat();
			myInterval = setInterval('updateChat()', interval_delay);
			$('.status').html('<span class="chatstatus" style="background-color:#14e800;">&nbsp;</span>');
			is_interval_running = true;
		}).blur(function () {
			clearInterval(myInterval);
			is_interval_running = false;
			$('.status').html('<span class="chatstatus" style="background-color:#e10000;">&nbsp;</span>');
		});
	});
// Update if focused END
// Online list
listonline = 0;
onetimer = 0;
function updateList() {
	if(listonline == 0) {
		if(onetimer == 0) {
			$('#onlinescreen').html('<img src="/images/loading.gif" width="30px" height="30px" alt="Loading..."><br>Loading...');
			onetimer = 1;
		}
		$.get("online.php?room=<?php echo $getroom; ?>", function(resultsl) {
			if(resultsl != '') {
				$("#onlinescreen").html(resultsl);
			}
			listonline = 1;
		});
	}
else {
	listonline = 0;
}
}

updateChat();

$(document).ready(function () {
	$('.sendbutton').submit(function () {
		var msg = document.getElementById("msg").value;
		var trimmed = $.trim(msg);
		if(trimmed == '') {
			$('#msg').attr("placeholder", "No blank posts."); // I've got a blank space bby
		}
		else {
			$('.status').html('<span class="chatstatus" style="background-color:#2c5fff;">&nbsp;</span>');
			$.post('/chat/com.php?room=<?php echo $getroom; ?>', $('.sendbutton').serialize(), function (data, msg) {
				if(data == 'ok') {
					$('#msg').attr("placeholder", "Type something...");
					updateChat();
				$('.status').html('<span class="chatstatus" style="background-color:#14e800;">&nbsp;</span>');
				}
				if(data == 'proxy') {
					$('#msg').attr("placeholder", "No proxies allowed.");
					$('.status').html('<span class="chatstatus" style="background-color:#14e800;">&nbsp;</span>');
				}
				if(data == 'flood') {
					$('#msg').attr("placeholder", "Type something...");
					alert('Slow down.');
				}
			});
		}
		$("#msg").val('');
    	return false;
	});
});


$(document).ready(function () {
	$('.cb1').on('tap', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
		$('.list1').stop(true,false).toggle(300);
    })
    $('.cb2').on('tap', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
		$('.list2').stop(true,false).toggle(300);
		updateList();
    })
});
function imgError(image) {
    image.onerror = "";
    image.src = "/images/bimg.png";
    return true;
}

// MENU CODE START
$(document).ready(function () {
    $('.menu').on('tap', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	$('#menulist').stop(true,false).slideToggle(350);
	updateMenu();
    })
});
function updateMenu() {
	$.get("/elements/menu.php", function(resultsx) {
		$("#menulist").html(resultsx);
	});
}
// MENU CODE END
</script>
</body>
</html>
