<?php
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

$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0')
{
	echo '<meta http-equiv="Refresh" content="0;url=/logout">';
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$token = $row['token'];

// _POST check
if(isset($_POST['user'])) {
	$user = $_POST['user'];
	$user = mysqli_real_escape_string($conn, $user);
	$user = htmlentities($user, ENT_QUOTES);
	$user = trim($user);
}
// _GET check
elseif(isset($_GET['user'])) {
	$user = $_GET['user'];
	$user = mysqli_real_escape_string($conn, $user);
	$user = htmlentities($user, ENT_QUOTES);
	$user = trim($user);
}
// none (fallback)
else {
	$user = '';
}

$userq = mysqli_query($conn, "SELECT username,disabled,ip,disabledtime FROM members WHERE username='$user' COLLATE utf8mb4_general_ci");
$userchkr = mysqli_fetch_assoc($userq);
$disabledlist = $userchkr['disabled'];
$userlist = $userchkr['username'];
$iplist = $userchkr['ip'];
$disabledtimelist = $userchkr['disabledtime'];
$userchkn = mysqli_num_rows($userq);
if ($disabled == 'yes') {
	echo '<meta http-equiv="Refresh" content="0;url='.$denyuseraccess.'">';
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if ($admin > 3) {
	echo '<meta http-equiv="Refresh" content="0;url=/">';
	exit;
}

if($user != '' && $user != ' ') {
	if($userchkn == 1) {
		function ago($time) {
			$periods = array("s", "m", "h", "d", "w", "mo", "y", "de");
			$lengths = array("60","60","24","7","4.35","12","10");
			$now = time();
			$difference = $now - $time;
			$tense = "ago";
			for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
				$difference /= $lengths[$j];
			}
			$difference = round($difference);
			if($difference != 1) {
				$periods[$j].= "";
			}
			return "$difference{$periods[$j]}";
		}
		$timeslice = strpos(ago($disabledtimelist),'-');
		if($timeslice === False) {
			if($disabledtimelist == '') {
				$time = 'PERM';
			}
			else {
				$time = 'PEND..';
			}
		}
		else {
				$time = str_replace('-','', ago($disabledtimelist));
		}
		echo '<div class="tab">Info</div>';
		echo '<div id="content" style="text-align:center;">';
		echo 'Username: '.$userlist;
		echo '<br>';
		echo 'Registration IP: '.$iplist;
		echo '<br>';
		if($disabledlist == 'yes') {
			echo 'State: Suspended';
			echo '<br>';
			echo 'Time remaining: '.$time.'</div>';
			}
		else {
			echo 'State: Unsuspended';
		}
		echo '</div>';
		echo '<div class="tab">Padlock</div>';
		echo '<div style="vertical-align:4px;background-color:white;">&nbsp</div>';
		echo '<form method="post" action="alterlock.php?user='.$user.'">';
        ?>
		<select name="locktime">
		<option value="fivemins" selected="selected">5 Minutes</option>
		<option value="tenmins">10 Minutes</option>
		<option value="thirtymins">30 Minutes</option>
		<option value="onehour">1 Hour</option>
		<option value="fivehours">5 Hours</option>
		<option value="tenhours">10 Hours</option>
		<option value="twentyhours">20 Hours</option>
		<option value="oneday">1 Day</option>
		<option value="twodays">2 Days</option>
		<option value="threedays">3 Days</option>
		<option value="fourdays">4 Days</option>
		<option value="fivedays">5 Days</option>
		<option value="sixdays">6 Days</option>
		<option value="oneweek">1 Week</option>
		<?php
    if($admin == 1) {
			echo '<option value="perma">Permanent</option>';
		}
		else {
			echo '<option disabled="disabled">Permanent</option>';
		}
		echo '</select>';
		echo '<div class="lockbuttonsbox">';
		echo '<input type="submit" class="lockbuttonfirst" name="lock" value="Unsuspend">&nbsp;&nbsp;<input type="submit" class="lockbuttonsecond" name="lock" value="Suspend">';
		echo '</form>';
		echo '</div>';
	}
	else {
		echo '<div class="nullbar">None</div>';
		echo '<div class="lockbuttonsbox">';
		echo 'User not found.';
		echo '<br>';
		echo '<br>';
		echo 'If this user does exist, please press \'ENTER\' for it to update.';
		echo '</div>';
	}
}
else {
		echo '<div class="nullbar">Enter a user</div>';
		echo '<div class="lockbuttonsbox">';
		echo 'Type the user\'s name into the text box and press ENTER.';
		echo '<br>';
		echo '<br>';
		echo 'Having a problem loading? Make sure your connection is fast enough.';
		echo '</div>';
}
mysqli_close($conn);
?>
