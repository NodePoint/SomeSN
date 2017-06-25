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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0' && isset($_COOKIE['username']) || $sesschecktwo == '0' && isset($_COOKIE['password'])) {
	header('/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if($sesschecktwo == 1) {
	mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SomeSN - Terms of Service</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="google" value="notranslate">
<meta name="theme-color" content="#4c43fa">
<link rel="icon" sizes="192x192" href="/images/somesn.png">
<meta name="mobile-web-app-capable" content="yes">
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="description" content="SomeSN - Terms of Service">
<meta name="keywords" content="Social, Network, Mobile, Tablet, PC, Phone, Chat, Status, Apps">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">ToS</a></span></div>
<?php
if($sesschecktwo == 1) {
	echo '<div class="menu"><span class="menutext"></span></div>';
	echo '<div id="menulist">';
	include '../elements/menu.php';
	echo '</div>';
}
?>
<div id="sep">&nbsp;</div>
<center>
<div id="barblock">
<div style="font-size:70%;color:grey;padding-bottom:1px;">Last Updated: 14th October 2015</div>
So you want to know how to fit in? This should get you started.
</div>
<div class="tab">Definitions</div>
<div style="background-color:white;padding:5px;text-align:left;">
<span style="font-size:110%;font-weight:bold;">Placeholder:</span> Just a placeholder.
</div>
<div class="tab">Global</div>
<div style="background-color:white;padding:5px;text-align:left;">
Global refers to all of the terms that apply to the whole site.
<br>
<br>
<span id="s1" style="font-size:130%;">1.0 - Dummy</span>
<br>
<br>
<span style="font-size:110%;font-weight:bold;">1.1 - Something:</span> More info.
<br>
<br>
<span id="s2" style="font-size:130%;">2.0 - Dummy 2</span>
<br>
<br>
Explaination of what this section is about.
<br>
<br>
<span style="font-size:110%;font-weight:bold;">2.1 - Something 2:</span> You get the point.
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
<?php if($sesschecktwo == 1) { ?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script type="text/javascript">
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
	var xmlhttp;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {
		// code for IE6, IE5
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
</script>
<?php } ?>
</body>
</html>
