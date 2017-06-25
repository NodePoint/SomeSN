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

# TOR Exit Node Check
function ReverseIPOctets($inputip) {
	$ipoc = explode(".",$inputip);
	return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
}
function IsTorExitPoint(){
	if(gethostbyname(ReverseIPOctets($_SERVER['REMOTE_ADDR']).".".$_SERVER['SERVER_PORT'].".".ReverseIPOctets($_SERVER['SERVER_ADDR']).".ip-port.exitlist.torproject.org")=="127.0.0.2") {
		return true;
	} else {
		return false;
	} 
}
if(IsTorExitPoint()) exit('You cannot perform actions while behind a proxy due to potential abuse. Please use a normal connection.');

$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,token,points FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$points = $row['points'];
$token = $row['token'];
if(isset($_GET['id'])) {
	$did = $_GET['id'];
	$did = mysqli_real_escape_string($conn, $did);
	$did = htmlentities($did, ENT_QUOTES);
}
else {
	$did = '';
}
if(isset($_GET['suser'])) {
	$suser = $_GET['suser'];
	$suser = mysqli_real_escape_string($conn, $suser);
	$suser = htmlentities($suser, ENT_QUOTES);
}
else {
	$suser = '';
}
if(isset($_GET['token'])) {
	$gettoken = $_GET['token'];
	$gettoken = mysqli_real_escape_string($conn, $gettoken);
	$gettoken = htmlentities($gettoken, ENT_QUOTES);
}
else {
	$gettoken = '';
}
$idc2 = mysqli_query($conn, "SELECT id,user,pointcount,postcount,points FROM profile_comments WHERE id='$did'");
$idc = mysqli_num_rows($idc2);
$row2 = mysqli_fetch_assoc($idc2);
$username = $row2['user'];
$pointcount = $row2['pointcount'];
$postcount = $row2['postcount'];
$pointsc = $row2['points'];
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if(isset($_GET['id']) && $did != '' && $did != ' ' && $gettoken == $token) {
	if ($idc == 1 && $admin < 4 && $username != $logged) {
		mysqli_query($conn, "DELETE FROM profile_comments WHERE id='$did'");
		if ($postcount > 0) {
			$update = mysqli_query($conn, "UPDATE members SET postcount='$postcount'-1 WHERE username='$username'");
		}
		if ($pointcount > 0) {
			$update = mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'-1 WHERE username='$username'");
			if ($pointcount == 0) {
				$update = mysqli_query($conn, "UPDATE members SET points='$pointsc'-1 WHERE username='$username'");
				$update = mysqli_query($conn, "UPDATE members SET pointcount='10' WHERE username='$username'");
				}
		}
	header('Location: /user/'.$suser);
	exit;
}
else {
	if ($username == $logged && $gettoken == $token) {
		// Point managing START
		if ($pointcount > 0 && $pointsc != 0) {
			$update = mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'-1 WHERE username='$logged'");
			if ($postcount > 0) {
				$update = mysqli_query($conn, "UPDATE members SET postcount='$postcount'-1 WHERE username='$logged'");
			}
			if ($pointcount == 0 && $pointsc != 0) {
				$update = mysqli_query($conn, "UPDATE members SET points='$pointsc'-1 WHERE username='$logged'");
			}
			$update = mysqli_query($conn, "UPDATE members SET pointcount='10' WHERE username='$logged'");
		}
	// Point managing END
	mysqli_query($conn, "DELETE FROM profile_comments WHERE id='$did'");
	header('Location: /user/'.$suser);
	exit;
}
else {
	header('Location: /user/'.$suser);
	exit;
}
}
}
else {
	header('Location: /user/'.$suser);
	exit;
}
mysqli_close($conn);
?>
