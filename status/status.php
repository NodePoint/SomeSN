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
if(isset($_GET['id'])) {
	$idget = $_GET['id'];
	$idget = mysqli_real_escape_string($conn, $idget);
	$idget = htmlentities($idget, ENT_QUOTES);
	$idgetch = mysqli_query($conn, "SELECT * FROM status_log WHERE id='$idget'");
	$idgetc = mysqli_num_rows($idgetch);
}
else {
	$idgetc = 0;
}
$sessjack = $select = mysqli_query($conn, "SELECT * FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$ip = $_SERVER['REMOTE_ADDR'];
$n = mysqli_query($conn, "SELECT * FROM members WHERE ip='$ip'");
$i = mysqli_num_rows($n);
$admin = $row['admin'];
$id = $row['id'];
$disabled = $row['disabled'];
$points = $row['points'];
$status_auto = $row['status_auto'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn, "UPDATE members SET time='".time()."' WHERE username='$logged'");
?>
<!DOCTYPE html>
<head>
<link rel="icon" type="image/png" href="/images/somesn.png">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
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
function imgError(image) {
	image.onerror = "";
	image.src = "/images/bimg.png";
	return true;
}
</script>
</head>
<body>
<div id="title"><a href="/status"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Status</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<?php
if(!isset($_GET['id'])) {
	header('location:/status');
	exit;
}

// I REALLY DON'T NEED TO BE COMMENTING ON THE REST OF THIS SCRIPT -- EVEN SOMEONE WHO DOESN'T KNOW PHP WOULD KNOW HOW BAD THIS IS

if(isset($_GET['id']) && $idget == '') {
echo '<title>SomeSN - Status ID blank</title>';
echo '<div id="content" style="text-align:center;padding:0px;">';
echo '<div id="info" style="border-radius:5px 5px 0px 0px;">Logged in as '.$logged.'</div>';
echo '<br>';
echo '<font color="red">The status ID is blank, please specify one.</font>';
echo '<br>';
echo '<br>';
echo '</div>';
}
if(isset($_GET['id']) && $idget != '' && $idgetc == '0') {
echo '<title>SomeSN - No such status</title>';
echo '<div id="content" style="text-align:center;padding:0px;">';
echo '<div id="info" style="border-radius:5px 5px 0px 0px;">Logged in as '.$logged.'</div>';
echo '<br>';
echo '<font color="red">Status doesn\'t exist!</font>';
echo '<br>';
echo '<br>';
echo '</div>';
}
if(isset($_GET['id']) && $idget != '' && $idget != ' ' && $idgetc == '1') {
echo '<form id="sendbutton" method="post" action="sub-comment.php?id='.$idget.'" autocomplete="off">';
echo '<div id="textboxmsg">';
echo '<div style="height:5px;">&nbsp;</div>';
echo '<textarea id="msg" class="ta" name="msg" type="text" class="textfield" maxlength="1000" placeholder="Enter your message..." style="resize:none;"></textarea>';
echo '<button type="submit" class="sendbuttonaction">Post</button>';
echo '</div>';
echo '<div class="tab">Status</div>';
echo '<div style="background-color:white;">';
echo '<br>';
function ago($time)
{
   $periods = array("s", "m", "h", "d", "w", "mo", "y", "de");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "";
   }

   return "$difference{$periods[$j]}";
}
while($msgs = mysqli_fetch_assoc($idgetch))
{
$idc = $msgs['id'];
$uidc = $msgs['uid'];
$username = $msgs['username'];
$message = $msgs['message'];
$ipaddr = $msgs['ip'];
$colourc = $msgs['colour'];
$ccount = mysqli_query($conn, "SELECT * FROM status_comments WHERE sid='$idc'");
$ccount = mysqli_num_rows($ccount);
$lcount = mysqli_query($conn, "SELECT * FROM status_likes WHERE sid='$idc'");
$lcount = mysqli_num_rows($lcount);
$date = $msgs['date'];
$time = $msgs['time'];
$ts = $msgs['timestamp'];
$select2 = mysqli_query($conn, "SELECT * FROM members WHERE username='$username'");
$row3 = mysqli_fetch_assoc($select2);
$rankmsg = $row3['admin'];
$avatars = $row3['avatar'];
$icon = $row3['icon'];
include $_SERVER['DOCUMENT_ROOT'].'/chat/censors.php';
include $_SERVER['DOCUMENT_ROOT'].'/chat/extras.php';
echo '<title>SomeSN - '.$username.'\'s Status</title>';
// Edit button
/* <span style="display:inline-block;float:right;background-color:#649fff;color:white;font-weight:bold;border:0px solid;border-radius:0px;" onclick="var conf=confirm(\'Edit post?\'); if (conf==true){window.location = \'edit/?id='.$idc.'\';}">&nbsp;Edit&nbsp;</span>
*/
if ($admin == 1 && $username != $logged) {
echo '<div id="statuses"><img id="statusavatars" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
}
echo '>&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div><br>';
}
else {
if ($admin == 1 && $username == $logged) {
echo '<div id="statuses"><img id="statusavatars" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
}
echo '>&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div><br>';
}
if ($admin < 4 && $admin != 1 && $username != $logged) {
echo '<div id="statuses"><img id="statusavatars" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
}
echo '>&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div><br>';
}
else {
if ($admin < 4 && $admin != 1 && $username == $logged) {
echo '<div id="statuses"><img id="statusavatar" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . '"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
}
echo '>&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div><br>';
}
if ($username != $logged && $admin > 3) {
echo '<div id="statuses"><img id="statusavatar" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . '">&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">'. nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div><br>';
}
else {
if ($username == $logged && $admin > 3) {
echo '<div id="statuses"><img id="statusavatar" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . '"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'\';}"';
}
}
echo '>&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div><br>';
}
}
}
}
}
echo '</div>';
$idgetchk = mysqli_query($conn, "SELECT * FROM status_comments WHERE sid='$idget' ORDER BY id DESC");
$stc = mysqli_num_rows($idgetchk);
if ($stc == 0) {
echo '<div style="background-color:white;">';
echo '<div class="tab">Comments <span id="circleind">'.$stc.'</span></div>';
echo '<br>There are no comments yet<br><br>';
echo '</div>';
echo '</div>';
}
else {
echo '<div style="background-color:white;">';
if($stc > 99) {
			echo '<div class="tab">Comments <span id="circleind">+</span></div>';
		}
		else {
			echo '<div class="tab">Comments <span id="circleind">'.$stc.'</span></div>';
		}
echo '<br>';
while($msgs = mysqli_fetch_assoc($idgetchk))
{
$idc = $msgs['id'];
$sidc = $msgs['sid'];
$uidc = $msgs['uid'];
$username = $msgs['user'];
$message = $msgs['message'];
$ipaddr = $msgs['ip'];
$colourc = $msgs['colour'];
$lcount = mysqli_query($conn, "SELECT * FROM status_likes WHERE sid='$idc' AND comnt='1'");
$lcount = mysqli_num_rows($lcount);
$date = $msgs['date'];
$time = $msgs['time'];
$ts = $msgs['timestamp'];
$select2 = mysqli_query($conn, "SELECT * FROM members WHERE username='$username'");
$row3 = @mysqli_fetch_assoc($select2);
$rankmsg = $row3['admin'];
$avatars = $row3['avatar'];
$icon = $row3['icon'];
include $_SERVER['DOCUMENT_ROOT'].'/chat/censors.php';
include $_SERVER['DOCUMENT_ROOT'].'/chat/extras.php';
if ($admin == 1 && $username != $logged) {
echo '<div id="statuses" style="width:90%;"><img id="statuscomimg" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Delete post?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&sid='.$idget.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
}
else {
if ($admin == 1 && $username == $logged) {
echo '<div id="statuses" style="width:90%;"><img id="statuscomimg" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Delete post?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&sid='.$idget.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
}
if ($admin < 4 && $admin != 1 && $username != $logged) {
echo '<div id="statuses" style="width:90%;"><img id="statuscomimg" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Delete post?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&sid='.$idget.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
}
else {
if ($admin < 4 && $admin != 1 && $username == $logged) {
echo '<div id="statuses" style="width:90%;"><img id="statuscomimg" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Delete post?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&sid='.$idget.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
}
if ($username != $logged && $admin > 3) {
echo '<div id="statuses" style="width:90%;"><img id="statuscomimg" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';">&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
}
else {
if ($username == $logged && $admin > 3) {
echo '<div id="statuses" style="width:90%;"><img id="statuscomimg" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Delete post?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&sid='.$idget.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
switch ($rankmsg) {
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
}
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
}
}
}
}
}
}
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
<?php mysqli_close($conn); ?>
