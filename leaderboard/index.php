<?php
error_reporting(1);
session_start();
if(isset($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'])) {
	$_SERVER['DOCUMENT_ROOT'] = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'];
}
require $_SERVER['DOCUMENT_ROOT'].'/elements/conn.php';
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
$sessjack = $select = mysqli_query($conn, "SELECT * FROM members WHERE username='$logged' AND password='$password'"); // "SELECT *" ? MHMM..
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0')
{
	header('location:/logout');
	exit;
}
$result = mysqli_query($conn, 'SELECT * FROM members ORDER BY points DESC LIMIT 0 , 4');
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$id = $row['id'];
$disabled = $row['disabled'];
$points = $row['points'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn, "UPDATE members SET time='".time()."' WHERE username='$logged'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SomeSN - Leaderboard</title>
<link rel="icon" type="image/png" href="/images/somesn.png">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script language="javascript" type="text/javascript">
// MENU CODE START
$(document).ready(function () {
    $('.menu').on('tap', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
		$('#menulist').stop(true,false).fadeToggle(200);
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
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Leaderboard</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<?php
while($row = mysqli_fetch_assoc($result)) {
$username = $row['username'];
$icon = mysqli_query($conn, "SELECT * FROM members WHERE username='$username'");
$icon = mysqli_fetch_assoc($icon);
$icon = $icon['icon'];
  echo '<div id="statuses" style="font-size:20px;max-width:100%;padding:10px;text-align:left;">';
  echo '<img src="' . $row['avatar'] . '" onerror="imgError(this);" height="70" width="70" style="border:0px solid;border-radius:15px;">';
  echo '&nbsp;&nbsp;<span onclick="location.href=\'/user/'.$username.'\'" style="color:'.$row['colour'].';font-weight:bold;cursor:pointer;">'.$username.'</span>&nbsp;-&nbsp;';
switch($row['admin']) {
	case 1:
		echo 'Owner';
		break;
	case 2:
		echo 'Admin';
		break;
	case 3:
		echo 'Mod';
		break;
	case 4:
		echo 'Trial';
		break;
	case 5:
		echo 'User';
		break;
	default:
		echo 'N/A';
		break;
}
  echo '<hr>';
  echo '<div style="text-align:left;">';
  echo 'Points: '.$row['points'];
  echo '</div>';
  echo '</div>';
  }
?>
<div id="footer">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/usercounter.php'; ?>
</div>
</center>
</body>
</html>
