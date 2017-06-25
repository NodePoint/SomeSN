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
if(IsTorExitPoint()) exit('<div class="clickablesearch">You cannot perform actions while behind a proxy due to potential abuse. Please use a normal connection.</div>');

if(isset($_POST['q'])) {
	$q = $_POST['q'];
	$q = mysqli_real_escape_string($conn, $q);
	$q = htmlentities($q, ENT_QUOTES);
	$q = trim($q);
}
else {
	$q = '';
}
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	echo '<meta http-equiv="Refresh" content="0;url=/logout">';
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
if($disabled == 'yes') {
	echo '<meta http-equiv="Refresh" content="0;url='.$denyaccessurl.'">';
	exit;
}

mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");

$usr2 = mysqli_query($conn, "SELECT username,disabled FROM members WHERE username LIKE '%{$q}%' COLLATE utf8mb4_general_ci"); // while loop main query
$usrchk = mysqli_num_rows($usr2); // check how many results
echo '<div>';
if($q != '' && $q != ' ' && $usrchk > 0) {
	echo '<div class="clickablesearch">Select a user:</div>';
	echo '<hr class="hrngap">';
	$countr = 1;
	while($usr = mysqli_fetch_assoc($usr2)) {
		// fix case insensitivity bolding
		$bold = str_ireplace($q,'<b>'.$q.'</b>', $usr['username']); // replaces (case insensitively) user query with user query using bold tags, main thing being replaced is the username result
		if($usr['disabled'] == 'yes') {
			echo '<div class="clickablesearch" onclick="location.href=\''.$usr['username'].'\'" style="color:red;">'.$bold.'</div>'; // if user is suspended
			echo '<hr class="hrngap">';
		}
		else {
		echo '<div class="clickablesearch" onclick="location.href=\''.$usr['username'].'\'">'.$bold.'</div>';
		echo '<hr class="hrngap">';
		}
	}
	echo '</div>';
}
mysqli_close($conn);
?>
