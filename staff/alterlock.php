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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,points,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$points = $row['points'];
$disabled = $row['disabled'];
if(isset($_GET['user']) && isset($_POST['locktime']) && isset($_POST['lock'])) {
	$user = $_GET['user'];
	$user = mysqli_real_escape_string($conn, $user);
	$user = htmlentities($user, ENT_QUOTES);
	$user = trim($user);
	$locktime = $_POST['locktime'];
	$locktime = mysqli_real_escape_string($conn, $locktime);
	$locktime = htmlentities($locktime, ENT_QUOTES);
	$locktime = trim($locktime);
	$lock = $_POST['lock'];
	$lock = mysqli_real_escape_string($conn, $lock);
	$lock = htmlentities($lock, ENT_QUOTES);
	$lock = trim($lock);
}
$userrow = mysqli_query($conn, "SELECT username FROM members WHERE username='$user' COLLATE utf8mb4_general_ci");
$userchk = mysqli_num_rows($userrow);
$rows = mysqli_fetch_assoc($userrow);
$user = $rows['username'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if($admin > 3) {
	header('location:/');
	exit;
}

$multi = '';

$multi .= "UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged';";
if($userchk == 1) {
	switch($lock) {
		case 'Unsuspend':
			$multi .= "UPDATE members SET disabled='',disabledreason='',disabledtime='' WHERE username='$user';";
			break;
		case 'Suspend':
			$multi .= "UPDATE members SET disabled='yes',disableduser='$logged' WHERE username='$user';";
				switch($locktime) {
					case 'fivemins':
						$multi .=  "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+300 WHERE username='$user';";
						break;
					case 'tenmins':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+600 WHERE username='$user';";
						break;
					case 'thirtymins':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+1800 WHERE username='$user';";
						break;
					case 'onehour':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+3600 WHERE username='$user';";
						break;
					case 'fivehours':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+18000 WHERE username='$user';";
						break;
					case 'tenhours':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+36000 WHERE username='$user';";
						break;
					case 'twentyhours':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+72000 WHERE username='$user';";
						break;
					case 'oneday':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+86400 WHERE username='$user';";
						break;
					case 'twodays':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+172800 WHERE username='$user';";
						break;
					case 'threedays':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+259200 WHERE username='$user';";
						break;
					case 'fourdays':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+345600 WHERE username='$user';";
						break;
					case 'fivedays':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+432000 WHERE username='$user';";
						break;
					case 'sixdays':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+518400 WHERE username='$user';";
						break;
					case 'oneweek':
						$multi .= "UPDATE members SET disabledtime=UNIX_TIMESTAMP(NOW())+604800 WHERE username='$user';";
						break;
					}
}
	if($admin == 1 && $locktime == 'perma') {
		$multi .= "UPDATE members SET disabledtime='',disabled='yes' WHERE username='$user';";
	}
}

mysqli_multi_query($conn, $multi); // THIS IS ONE OF THE THINGS I'M PROUD OF USING

header('location:/staff/?user='.$user);
mysqli_close($conn);
exit;
?>
