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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,admin,disabledreason,colour,points,admin FROM members WHERE username='$logged' AND password='$password'");
$sessjacktwo = $count = mysqli_num_rows($sessjack);
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
$admin = $row['admin'];
$disabledreason = $row['disabledreason'];
$colour = $row['colour'];
$points = $row['points'];
$admin = $row['admin'];
// THESE HEADERS SHOULD REALLY BE ON THE VERY TOP
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
$select3 = mysqli_query($conn, 'SELECT id FROM status_log');
$stc = mysqli_num_rows($select3);
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
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
if ($stc == 0) {
echo '<div id="statuses" style="text-align:center;"><br>This status page is empty; post something!<br><br></div>';
}
else {
$msgs2 = mysqli_query($conn,'SELECT id,username,message,ip,colour,date,time FROM status_log ORDER BY id DESC LIMIT 0 , 13');
while($msgs = mysqli_fetch_assoc($msgs2)) {
$idc = $msgs['id'];
$username = $msgs['username'];
$message = $msgs['message'];
$ipaddr = $msgs['ip'];
$colourc = $msgs['colour'];
$ccount = mysqli_query($conn, "SELECT sid FROM status_comments WHERE sid='$idc'");
$ccount = mysqli_num_rows($ccount);
$lcount = mysqli_query($conn, "SELECT sid FROM status_likes WHERE sid='$idc'");
$lcount = mysqli_num_rows($lcount);
$date = $msgs['date'];
$time = $msgs['time'];
$select2 = mysqli_query($conn, "SELECT username,admin,avatar,icon FROM members WHERE username='$username'");
$row3 = mysqli_fetch_assoc($select2);
$rankmsg = $row3['admin'];
$avatars = $row3['avatar'];
$icon = $row3['icon'];
if($sessjacktwo == '0') {
echo '<meta http-equiv="Refresh" content="3;url=/logout">';
echo '<center><span style="color: red; font-weight: bold;">Account Authentication Failed</span></center>';
exit();
}
if ($disabled == 'yes') {
die('<center><span style="color: red; font-weight: bold;">Your account has been suspended.</center>');
}
// Edit button
/* <span style="display:inline-block;float:right;background-color:#649fff;color:white;font-weight:bold;border:0px solid;border-radius:0px;" onclick="var conf=confirm(\'Edit post?\'); if (conf==true){window.location = \'edit/?id='.$idc.'\';}">&nbsp;Edit&nbsp;</span>
*/

// DON'T ASK ABOUT THE REST OF THE CODE IN THIS SCRIPT -- I DON'T EVEN KNOW HOW I MANAGED TO WORK WITH IT

if ($admin == 1 && $username != $logged) {
echo '<div id="statuses"><img id="statusavatars" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span class="statusdelete" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
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
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div>';
}
else {
if ($admin == 1 && $username == $logged) {
echo '<div id="statuses"><img id="statusavatars" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span class="statusdelete" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
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
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div>';
}
if ($admin < 4 && $admin != 1 && $username != $logged) {
echo '<div id="statuses"><img id="statusavatars" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span class="statusdelete" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
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
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div>';
}
else {
if ($admin < 4 && $admin != 1 && $username == $logged) {
echo '<div id="statuses"><img id="statusavatar" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . '"><span class="statusdelete" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
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
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div>';
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
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">'. nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div>';
}
else {
if ($username == $logged && $admin > 3) {
echo '<div id="statuses"><img id="statusavatar" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . '"><span class="statusdelete" ';
if ($ccount > 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comments associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
if ($ccount == 1) {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status along with the comment associated with it?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
}
else {
echo 'onclick="var conf=confirm(\'Are you sure you want to delete this status?\'); if (conf==true){window.location = \'delst.php?id='.$idc.'&&token='.$token.'\';}"';
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
echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:38%;display:inline-block;text-align:center;padding:0.6%;padding-right:0.08%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px;display:inline-block;width:21.77%;text-align:center;padding:0.6%;padding-right:0.29%;" onclick="alert(\'This feature is not implemented yet.\');">Report</span><span style="background-color:#7396ff;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;float:right;width:38%;text-align:center;padding:0.6%;padding-left:0.06%;" onclick="location.href=\'/status/status.php?id='.$idc.'\'">Comments ('.$ccount.')</span><br></div>';
}
}
}
}
}
}
mysqli_close($conn);
?>
