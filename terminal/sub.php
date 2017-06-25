<?php
if(!isset($sessexists)) session_start();
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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	echo '<meta http-equiv="Refresh" content="0;url=/logout">';
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
if ($disabled == 'yes') {
	echo '<meta http-equiv="Refresh" content="0;url='.$denyaccessurl.'">';
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if ($admin > 2) {
	echo '<meta http-equiv="Refresh" content="0;url=/">';
	exit;
}
if(isset($_POST['cmd'])) {
	$cmd = $_POST['cmd'];
	$cmd = mysqli_real_escape_string($conn, $cmd);
	$cmd = str_replace('­','',$cmd);
	$cmd = htmlentities($cmd, ENT_QUOTES);
	$cmd = trim($cmd);
}
else {
	$cmd = '';
}
$cmdarr = explode(' ', $cmd);
$cmdvalid = False;
$cmdbad = False;
if(isset($_POST['cmd'])) {
if($cmd != '' && $cmd != ' ') {
	if($cmdarr[0] == 'check') { // arg: check [..]
		if($cmdarr[1] == 'USR_PRIV') { // arg: check USR_PRIV [username]
			$usrchk = mysqli_query($conn, "SELECT username,admin FROM members WHERE username='$cmdarr[2]'");
			$usrchkn = mysqli_num_rows($usrchk);
			if($usrchkn == 1) {
				$usrchkr = mysqli_fetch_assoc($usrchk);
				$username = $usrchkr['username'];
				$rank = $usrchkr['admin'];
				echo '-------------';
				echo '<br>';
				echo '- USER INFO -';
				echo '<br>';
				echo '-------------';
				echo '<br>';
				echo 'Username: '.$username;
				echo '<br>';
				echo 'Rank: ';
				switch($rank) {
					case 1:
						echo 'Owner';
						break;
					case 2:
						echo 'Admin';
						break;
					case 3:
						echo 'Moderator';
						break;
					case 4:
						echo 'Trial';
						break;
					case 5:
						echo 'User';
						break;
					default:
						echo 'NULL';
						break;
				}
				$cmdbad = True;
				$cmdvalid = True;
			}
			else {
				if($cmdarr[2] == '' || $cmdarr[2] == ' ') {
					$cmdvalid = True;
				}
				else {
					echo '<span style="color: red;">No such user.</span>';
					$cmdbad = True;
					$cmdvalid = True;
				}
			}
		}
		if($cmdarr[1] == '' || $cmdarr[1] == ' ') {
			$cmdvalid = True;
		}
	}
else {
	$cmdbad = True;
}
if($cmdbad === False) {
	echo '<span style="color: red;">Invalid argument.</span>';
}
if($cmdvalid === False) {
	echo '<span style="color: red;">Invalid command.</span>';
}
}
else {
	echo '<span style="color: red;">Input is blank.</span>';
}
}
else {
	echo ' - - - Welcome to the staff terminal. - - - ';
	echo '<br>';
	echo 'Go to the cmd page for the commands and usage.';
}
mysqli_close($conn);
?>
