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
$select = mysqli_query($conn, "SELECT * FROM members WHERE username='$logged'");
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
$user = $_POST['user'];
$user = mysqli_real_escape_string($conn, $user);
$user = htmlentities($user, ENT_QUOTES);
$pass = $_POST['pass'];
$pass = mysqli_real_escape_string($conn, $pass);
$pass = htmlentities($pass, ENT_QUOTES);
$pass = hash('sha256', sha1(md5('IDKBFoigbKIUEGIUgVB'.$pass.'dKDksbvbiksKBFSFS$AFNLAjs')));
// DO NOT USE THE METHOD OF HASHING AND SALTING USED HERE -- GO WITH A STRONGER HASH ONCE LIKE SHA512 AND USE SOMETHING LIKE BCRYPT
$ip = $_SERVER['REMOTE_ADDR'];
$n = mysqli_query($conn, "SELECT * FROM members WHERE ip='".$ip."'");
$i = mysqli_num_rows($n);
$check = mysqli_query($conn, "SELECT * FROM members WHERE username='$user' COLLATE utf8mb4_general_ci AND password='$pass'");
$check = mysqli_num_rows($check);
if(isset($_COOKIE['username']) || isset($_COOKIE['password'])) {
	header('location: /');
	exit;
}
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if($check == '1') {
	$loginfo = mysqli_query($conn, "SELECT * FROM members WHERE username='$user' COLLATE utf8mb4_general_ci");
	$loginfo = mysqli_fetch_assoc($loginfo);
	$usr = $loginfo['username'];
	setcookie('username', $usr, time()+10*365*24*60*60, '/', null, null, true); // COULD'VE ENABLED THE SECURE FLAG FOR THIS
	setcookie('password', $pass, time()+10*365*24*60*60, '/', null, null, true);
}
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<title>SomeSN - Login</title>';
echo '<meta charset="UTF-8">';
echo '<link rel="icon" type="image/png" href="/images/somesn.png">';
echo '<link rel="stylesheet" type="text/css" href="/css/styles.css">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
echo '<meta name="description" content="SomeSN - Login">';
echo '<meta name="keywords" content="SomeSN, Social, Network, Mobile, Tablet, PC, Phone, Chat, Status, Apps">';
include $_SERVER['DOCUMENT_ROOT'].'/elements/font.php';
echo '</head>';
echo '<body>';
echo '<div id="title"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Login</span></div>';
echo '<div id="sep">&nbsp;</div>';
echo '<center>';
if(isset($_POST['user']) || isset($_POST['pass']))
{
	// PLEASE AVOID THIS -- THERE ARE BETTER WAYS
	if($check == '1') {
		echo '<meta http-equiv="Refresh" content="2;url=/"><div id="success">Logged in, redirecting...</div><br><br><div id="content" style="padding:0px;text-align:center;"><div id="infobar">Logging in...</div><br><form method="post" action="" name="login">Username: <input name="user" type="text" maxlength="20" value="'.$user.'" disabled="disabled"><hr style="width:95%;">Password: <input name="pass" type="password" maxlength="100" style="font-color:#494949; disabled="disabled"></form><div id="submissionbutton" disabled="disabled">Login</div>';
	if ($i > 1) {
		echo '</div>';
		}
	else {
		echo '<div id="registerbutton" style="border-radius:0px 0px 5px 5px">Register</div>';
		echo '</div>';
	}
	}
	else {
		echo '<div id="error">Invalid username/password</div><br><br><div id="content" style="padding:0px;text-align:center;"><div id="infobar">Try again</div><br><form method="post" action="" name="login">Username: <input name="user" type="text" maxlength="20"><hr style="width:95%;">Password: <input name="pass" type="password" maxlength="100"></form><div id="submissionbutton" onclick="document.forms[\'login\'].submit();">Login</div>';
	if ($i > 1) {
		echo '</div>';
	}
	else {
		echo '<div id="registerbutton" style="border-radius:0px 0px 5px 5px" onclick="location.href=\'/register\'">Register</div>';
		echo '</div>';
	}
	}
	}
	else {
		echo '<br><br><center><div id="content" style="padding:0px;text-align:center;"><div id="infobar">Login below</div><br><form method="post" action="" name="login">Username: <input name="user" type="text"  maxlength="20"><hr style="width:95%;">Password: <input name="pass" type="password" maxlength="100"><input type="submit" value="Submit"></form><br><div id="submissionbutton" onclick="document.forms[\'login\'].submit();">Login</div>';
	if ($i > 1) {
		echo '</div>';
	}
	else {
		echo '<div id="registerbutton" style="border-radius:0px 0px 5px 5px" onclick="location.href=\'/register\'">Register</div>';
		echo '</div>';
	}
}
echo '</center>';
echo '<div id="footer">';
include $_SERVER['DOCUMENT_ROOT'].'/elements/usercounter.php';
echo '</div>';
echo '</body>';
echo '</html>';
?>
