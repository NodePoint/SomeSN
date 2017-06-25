<?php
session_start();
require 'elements/conn.php';

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
$ip = $_SERVER['REMOTE_ADDR'];
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$token = $row['token'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if(isset($_COOKIE['username']) && $sesschecktwo == '0' || isset($_COOKIE['password']) && $sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
if(!isset($_COOKIE['username']) && !isset($_COOKIE['password']) && $sesschecktwo == '0') { ?>
<!DOCTYPE html>
<head lang="en">
<meta charset="UTF-8">
<title>SomeSN</title>
<link rel="stylesheet" type="text/css" async defer href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="google" value="notranslate">
<meta name="theme-color" content="#4c43fa">
<link rel="icon" sizes="192x192" href="/images/somesn.png">
<meta name="mobile-web-app-capable" content="yes">
<meta name="description" content="A simple, yet powerful social networking site.">
<meta name="keywords" content="Social, Network, Mobile, Tablet, PC, Phone, Chat, Status, Apps, Store">
</head>
<body>
<div id="title"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">SomeSN</span></div>
<div id="sep">&nbsp;</div>
<center>
<br>
Welcome.
<br>
<br>
<div id="mainbutton" onclick="location.href='/login'" style="background-color:#dadada;">Login</div>
<br>
<div id="mainbutton" onclick="location.href='/register'" style="background-color:#dadada;">Register</div>
<br>
<div id="footer">
<?php include 'elements/usercounter.php'; ?>
</div>
</center>
</body>
</html>
<?php
}
else if(isset($_COOKIE['username']) && isset($_COOKIE['password']) && $sesschecktwo == '1') {
	mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
?>
<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="UTF-8">
<title>SomeSN - Home</title>
<link rel="stylesheet" type="text/css" async defer href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="google" value="notranslate">
<meta name="theme-color" content="#4c43fa">
<link rel="icon" sizes="192x192" href="/images/somesn.png">
<meta name="mobile-web-app-capable" content="yes">
</head>
<body>
<div id="title"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Home</span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include 'elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<div class="body">
<center>
<div id="barblock">
Hello, <b><?php echo $logged; ?>.</b>
<br>
Where do you want to go?
</div>
<div class="tab">Social</div>
<div style="background-color:white;overflow:hidden;max-width:100%;border:0px solid;">
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/status"><img class="menuicons" src="/images/status.png" alt="Status" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Status</span></div>
<hr class="hrngap">
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/chat/roomsel.php"><img class="menuicons" src="/images/chat.png" alt="Chat" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Chat</span></div>
<div class="tab">Stats</div>
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/leaderboard"><img class="menuicons" src="/images/leaderboard.png" alt="Leaderboard" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Leaderboard</span></div>
<div class="tab">Account</div>
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/user/<?php echo $logged; ?>"><img class="menuicons" src="/images/profile.png" alt="Profile" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Profile</span></div>
<hr class="hrngap">
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/account"><img class="menuicons" src="/images/gear.png" alt="Settings" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Settings</span></div>
<hr class="hrngap">
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/security"><img class="menuicons" src="/images/security.png" alt="Security" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Security</span></div>
<hr class="hrngap">
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/logout"><img class="menuicons" src="/images/logout.png" alt="Door" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Logout</span></div>
<?php
if ($admin < 4) {
echo '<div class="tab">Staff</div>';
echo '<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/staff"><img class="menuicons" src="/images/staff.png" alt="Staff area" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Staff</span></div>';
if ($admin < 3) {
echo '<hr class="hrngap">';
echo '<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/terminal"><img class="menuicons" src="/images/terminal.png" alt="Terminal" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Terminal</span></div>';
}
}
?>
<div class="tab">Apps</div>
<div class="listbuttons ripple" data-ripple-color="#ABABAB" data-source-url="/store"><img class="menuicons" src="/images/store.png" alt="Staff area" width="30px" height="30px">&nbsp;&nbsp;<span class="listbuttonstext">Store</span></div>
</div>
<div id="content" style="padding:0px;text-align:center;">
<div class="tab">Users Online <span id="circleind">
<?php include 'elements/usersonlinefullcount.php'; ?>
</span></div>
<div style="padding:4px;font-size:25px;">
<div id="onlineusers">
<?php include 'elements/usersonlinefull.php'; ?>
</div>
</div>
</div>
</div>
<div id="footer">
<?php include 'elements/usercounter.php'; ?>
</div>
</center>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {

    // MENU CODE START
	mslider = 0;
    $('.menu').on('tap', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$('#menulist').stop(true,false).slideToggle(450, function() {
		if(mslider == 0) {
			updateMenu();
			mslider = 1;
		}
		else if(mslider == 1) {
			mslider = 0;
		}
		});
    });

	function updateMenu() {
		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		}
		else {// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("menulist").innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","/elements/menu.php",true);
		xmlhttp.send(null);
	}
// MENU CODE END


$(".ripple").on("tap", function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	var url = $(this).data("source-url");
	var $div = $("<span/>"),
	btnOffset = $(this).offset(),
	xPos = e.pageX - btnOffset.left,
	yPos = e.pageY - btnOffset.top;
	$div.addClass("ripple-effect");
	var $ripple = $(".ripple-effect");
	$ripple.css("height", $(this).height());
	$ripple.css("width", $(this).height());
	$div
	.css({
	top: yPos - ($ripple.height()/2),
	left: xPos - ($ripple.width()/2),
	background: $(this).data("ripple-color")
	})
	.appendTo($(this));
	setTimeout(function(){
	$div.remove();
	if(url != "/logout") {
	location.href = url;
	}
	else {
		var conf = confirm('Are you sure you want to logout?');
		if (conf==true) {
			location.href = "/logout/?token=<?php echo $token; ?>";
		}
	}
	}, 500);
});
});
</script>
</body>
</html>
<?php
}
?>
