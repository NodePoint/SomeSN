<?php
require 'conn.php';

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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,points,token,admin FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	exit('<div style="margin-left:auto;margin-right:auto;text-align:center;padding:10px;">You\'re not logged on. Refresh.</div>');
}
else {
	$row = mysqli_fetch_assoc($select);
	$points = $row['points'];
	$token = $row['token'];
	$admin = $row['admin'];
	$pmsystem = mysqli_query($conn, "SELECT receiver,read_pm FROM pm_system WHERE receiver='$logged' AND read_pm='0'");
	$pmsystemc = mysqli_num_rows($pmsystem);
	/*
	echo '
	<script type="text/javascript">
	if (window.navigator && window.navigator.vibrate) {
		navigator.vibrate(1000);
	} else {
	}
	</script>
	';
	echo '
	<script type="text/javascript">
	if(window.Notification && Notification.permission !== "denied") {
	Notification.requestPermission(function(status) { 
		var n = new Notification(\'Title\', { body: \'this is the body\' }); 
	});
	}
	</script>
	';
	*/
	// YES, THAT WAS AN ATTEMPT TO USE THE VIBRATOR AND NOTIFICATION API FOR.. WELL, NOTIFICATIONS
	// THIS DID WORK WHEN INCLUDING BUT DIDN'T WHEN REQUESTED VIA XHR, OF COURSE
	
	echo '<div class="menutitle"><div class="menutitletext">Menu</div><span class="menumbars limemenu">';
	if($pmsystemc == 1) {
		echo $pmsystemc.' PM';
	}
	elseif($pmsystemc > 99) {
		echo '99+ PMs';
	}
	else {
		echo $pmsystemc.' PMs';
	}
	echo '</span> ';
	if($admin == 1) {
		echo '<span class="menumbars limemenu" onclick="location.href=\'/terminal\'">Terminal</span> ';
	}
	if($admin <= 3) {
		echo '<span class="menumbars limemenu" onclick="location.href=\'/staff\'">Staff</span> ';
	}
	echo '<span class="menumbars redmenu" onclick="var conf=confirm(\'Are you sure you want to logout?\'); if (conf==true){window.location = \'/logout/?token='.$token.'\';}">Logout</span></div>';
	$msg2 = mysqli_query($conn, "SELECT receiverusername,username,message,url,readyet FROM notification_system WHERE receiverusername='$logged' ORDER BY id DESC limit 0, 5");
	$msgchk = mysqli_num_rows($msg2);
	if($msgchk == 0) {
		echo '<div style="text-align:center;padding:10px;">There are currently no notifications.</div>';
	}
	else {
		while($msg = mysqli_fetch_assoc($msg2)) {
			if($msg['readyet'] == 0) {
				echo '<div class="notification unread" onclick="location.href=\''.$msg['url'].'\'"><b>'.$msg['username'].'</b><span class="notificationtime">Just now</span><br><span class="notificationdes">'.$msg['message'].'</span></div>';
				echo '<hr class="hrngap">';
			}
			else {
				echo '<div class="notification" onclick="location.href=\''.$msg['url'].'\'"><b>'.$msg['username'].'</b><span class="notificationtime">10m</span><br><span class="notificationdes">'.$msg['message'].'</span></div>';
				echo '<hr class="hrngap">';
			}
		}
	}
	echo '<div class="menubottom">'.$logged.'</div>';
	echo '</div>';
}
?>
