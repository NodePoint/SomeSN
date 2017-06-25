<?php
if(!isset($sess_set)) {
    session_start();
}
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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,admin FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	echo '<meta http-equiv="Refresh" content="0;url=/logout">';
	exit;
}
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
if($disabled == 'yes') {
	echo '<meta http-equiv="Refresh" content="0;url='.$denyaccessurl.'">';
	exit;
}
$admin = $row['admin'];
include('room.php');
$seconds = 60;
$msgs2 = mysqli_query($conn, "SELECT username,".$roomol.",colour,disabled,icon,flood FROM members WHERE ".$roomol." > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY username");
while($msgs = mysqli_fetch_assoc($msgs2)) {
$username = $msgs['username'];
$colourc = $msgs['colour'];
$time = $msgs[$roomol];
$disabledc = $msgs['disabled'];
$iconc = $msgs['icon'];
$flood = $msgs['flood'];
if($disabledc != 'yes') {
echo '<span style="color:'.$iconc.';">&#9679;</span> <span onclick="location.href=\'/user/'.$username.'\'" style="color:'.$colourc.';cursor:pointer;">'.$username.'</span>';
}
else {
echo '<s><span style="color:'.$iconc.';">&#9679;</span> <a href="/user/'.$username.'" style="color:'.$colourc.';">&nbsp;'.$username.'&nbsp;</a></s>';
}
if($flood == 1) {
echo ' <span style="background-color:red;color:white;font-size:12px;padding-left:2px;padding-right:2px;vertical-align:middle;">SpamLock</span>';
}
echo '<br>';
}
mysqli_close($conn);
?>
