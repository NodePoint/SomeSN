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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,points,status_auto FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$points = $row['points'];
$status_auto = $row['status_auto'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
?>
<!DOCTYPE html>
<head>
<title>SomeSN - Status</title>
<link rel="icon" type="image/png" href="/images/somesn.png">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Status</a></span></div><div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<?php
echo '<form id="sendbutton" method="post" autocomplete="off" action="sub-status.php">';
echo '<div id="content">';
echo '<div style="height:5px;">&nbsp;</div>';
echo '<textarea id="msg" class="ta" name="msg" onchange="input_count();" type="text" class="textfield" maxlength="1000" placeholder="Enter your message..." style="resize:none;"></textarea>';
echo '<div style="height:5px;">&nbsp;</div>';
echo '<button type="submit" class="mainbutton">Post (<span class="count"></span>)</button>';
echo '</div>';
if ($status_auto == 1) {
echo '<div class="olistc">Actions - <span id="circleol" class="cb1" onclick="var conf=confirm(\'Disable auto-updating status?\'); if (conf==true){window.location = \'usrup.php?tog=off\';}">Turn off Auto-Update</span></div>';
}
else {
echo '<div class="olistc">Actions - <span id="circleol" class="cb1" onclick="updateStatus();">Update</span> <span id="circleol" class="cb1" onclick="var conf=confirm(\'Enable auto-updating status?\nThis allows the status to update by itself without user interaction.\n\nNOTE: If you\\\'re on a slow connection, then issues may occur while this is on.\'); if (conf==true){window.location = \'usrup.php?tog=on\';}">Auto-Update</span></div>'; // WELL, THE ESCAPING IS RATHER UGLY FOR THIS ONE
}
?>
<div id="statusscreen">
<?php include 'sscreen.php'; ?>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
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
<?php
if ($status_auto == 1) {
// OH HELL NO.. NOT THIS
echo '<script type="text/javascript"> 
function updateStatus()
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
document.getElementById("statusscreen").innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET","sscreen.php",true);
xmlhttp.send();
}
setInterval(\'updateStatus()\',5000);
</script>';
}
else {
echo '<script type="text/javascript"> 
function updateStatus()
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
document.getElementById("statusscreen").innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET","sscreen.php",true);
xmlhttp.send();
}
</script>';
}
?>
<script language="javascript" type="text/javascript">
function imgError(image) {
    image.onerror = "";
    image.src = "/images/bimg.png";
    return true;
}
$(document).ready(function () {
function input_count() {
	var text_max = 1000;
    $('.count').html(text_max);

    $('.ta').keyup(function() {
        var text_length = $('.ta').val().length;
        var text_remaining = text_max - text_length;

        $('.count').html(text_remaining);
    });
}
input_count();
});
</script>

</body>
</html>
