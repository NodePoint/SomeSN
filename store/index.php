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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,points FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0')
{
	header('location:/logout');
	exit;
}
$result = mysqli_query($conn, "SELECT id,name,icon,path_title FROM app_list WHERE type = '1' ORDER BY id DESC LIMIT 0 , 4");
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
$points = $row['points'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SomeSN - Store</title>
<link rel="icon" type="image/png" href="/images/somesn.png">
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Store</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<div id="barblock">
This is the store. You can add features of your choice to your account either by using your points or for free.
</div>
<div class="tab">Modules</div>
<div style="background-color:white;text-align:left;">
<?php
$chk = mysqli_num_rows($result);
while($row1 = mysqli_fetch_assoc($result)) {
	$icon = $row1['icon'];
	$path_title = $row1['path_title'];
	$name = $row1['name'];
	echo '<a href="/store/app.php?app='.$path_title.'" style="color:black;"><span style="padding:10px;display:inline-block;text-align:center;overflow:hidden;">';
	echo '<img src="' . $icon . '" onerror="imgError(this);" height="50" width="50">';
	echo '<br><span style="display:inline-block;">';
	echo $name;
	echo '</span></span></a>';
}
?>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script language="javascript" type="text/javascript">
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
