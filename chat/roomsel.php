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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,kicked FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$kicked = $row['kicked'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if($kicked == 'yes') {
mysqli_query($conn, "UPDATE members SET kicked='' WHERE username='$logged'");
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
$seconds = 60;
?>
<!DOCTYPE html>
<head lang="en">
<title>SomeSN - Chatroom Selection</title>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
<meta name="google" value="notranslate">
<meta name="theme-color" content="#4c43fa">
<link rel="icon" sizes="192x192" href="/images/somesn.png">
<meta name="mobile-web-app-capable" content="yes">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Rooms</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<div id="barblock">
Select the chatroom you want to visit below. You can always visit other chatrooms while in a chatroom.
<br>
By using the chat, you agree to the <a href="/tos" style="color:blue;">ToS</a>.
</div>
<div style="background-color:white;padding-bottom:5px;">
<div style="line-height:5px;">&nbsp;</div>
<div class="tab">Rooms</div>
<div style="line-height:5px;">&nbsp;</div>
<div id="roombar" onclick="location.href='/chat/?room=orig'">Original (<?php
$ccount = 0;
$chatoriginal = mysqli_query($conn, "SELECT username,origchattime FROM members WHERE origchattime > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY username");
$chatoriginal = mysqli_num_rows($chatoriginal);
echo $chatoriginal;
?>
)</div>
<div style="line-height:5px;">&nbsp;</div>
<div id="roombar" onclick="location.href='/chat/?room=rp'">Roleplay (<?php
$chatroleplay = mysqli_query($conn, "SELECT username,rpchattime FROM members WHERE rpchattime > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY username");
$chatroleplay = mysqli_num_rows($chatroleplay);
echo $chatroleplay;
?>
)</div>
<?php
if($admin < 4) {
echo '<div style="line-height:5px;">&nbsp;</div>';
echo '<div id="roombar" onclick="location.href=\'/chat/?room=staff\'">Staff (';
$chatstaff = mysqli_query($conn, "SELECT username,staffchattime FROM members WHERE staffchattime > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY username");
$chatstaff = mysqli_num_rows($chatstaff);
echo $chatstaff;
echo ')</div>';
}
?>
<div style="line-height:5px;">&nbsp;</div>
<div id="roombar" onclick="location.href='/chat/?room=dev'">Developers (<?php
$chatdev = mysqli_query($conn, "SELECT username,devchattime FROM members WHERE devchattime > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY username");
$chatdev = mysqli_num_rows($chatdev);
echo $chatdev;
?>
)</div>
</div>
</center>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
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
function updateMenu()
{
var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp = new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange = function()
{
if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
{
document.getElementById("menulist").innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET","/elements/menu.php",true);
xmlhttp.send(null);
}
// MENU CODE END
</script>
</body>
</html>
