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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,admin,kicked FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
$admin = $row['admin'];
$kicked = $row['kicked'];
include('room.php');
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
mysqli_query($conn, "UPDATE members SET ".$roomol."=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if(!isset($sess_set)) {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

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

// Chat screen results
$msgs2 = mysqli_query($conn, 'SELECT `id`,`username`,`message`,`colour`,`sender`,`time`,`recep`,`bot` FROM (SELECT `id`,`username`,`message`,`colour`,`sender`,`time`,`recep`,`bot` FROM '.$roomtb.' ORDER BY id DESC LIMIT 25) AS sq ORDER BY ID ASC');

$msgchkr = mysqli_num_rows($msgs2);
$emptymsg = 'The chat is empty. Be the first one to post!'; // empty message
// not logged in
if($sesschecktwo == 0) {
	die('<meta http-equiv="Refresh" content="2;url=/logout"><div style="color:red;font-weight:bold;text-align:center;padding:9px;">Account Authentication Failed</div>');
}
// suspended
if($disabled == 'yes') {
	exit('<meta http-equiv="Refresh" content="2;url='.$denyaccessurl.'"><div style="color:red;font-weight:bold;text-align:center;padding:9px;">Your account has been suspended.</div>');
}
// kicked
if($kicked == 'yes') {
	exit('<meta http-equiv="Refresh" content="2;url=/chat/roomsel.php"><div style="color:red;font-weight:bold;text-align:center;padding:9px;">You\'ve been kicked.</div>'.$kicked);
}
// empty chat
if($msgchkr == 0) {
	echo '<div style="text-align:center;padding:9px;">'.$emptymsg.'</div>';
}
else {
	while($msgs = mysqli_fetch_assoc($msgs2)) {
		$colourc = $msgs['colour'];
		$idc = $msgs['id'];
		$sender = $msgs['sender'];
		$username = $msgs['username'];
		$message = $msgs['message'];
		$time = $msgs['time'];
		$recep = $msgs['recep'];
		$rliveinfo = mysqli_query($conn, "SELECT username,admin FROM members WHERE username='$sender'");
		$rrow = mysqli_fetch_assoc($rliveinfo);
		$adminr = $rrow['admin'];

		# CHAT SCREEN
		echo '<div>';
      	echo '<div class="chattopmsg">';
		echo '<span class="username" style="color:'.$colourc.';font-weight:bold;cursor:pointer;" onclick="location.href=\'/user/'.$sender.'\'">'.$username.'</span><span class="chatpriv">&nbsp;&nbsp;&nbsp;';
      	switch($adminr) {
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
				case 'Bot':
					echo 'Bot';
					break;
			}
      echo '</span><span class="chattime">'.ago($time).'</span>';
      echo '</div>';
      echo '<div class="chatmsg">';
      echo $message;
			// if whisper
			if($recep != '' && $recep != ' ' && $recep == $logged || $recep != '' && $recep != ' ' && $sender == $logged) {
			echo ' <span style="background-color:orange;padding:2px;padding-top:1px;padding-bottom:1px;font-size:10px;">To: '.$recep.'</span>';
			}
      echo '</div>';
      echo '</div>';
	}
}
mysqli_close($conn);
?>
