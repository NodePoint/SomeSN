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

$sessjack = $select1 = mysqli_query($conn, "SELECT username,password,colour,icon,disabled,points,ip,postcount,grammar,admin,pointcount,colourdis FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row1 = mysqli_fetch_assoc($select1);
$colour = $row1['colour'];
$icon = $row1['icon'];
$disabled = $row1['disabled'];
$points = $row1['points'];
$usrip = $row1['ip'];
$postcount = $row1['postcount'];
$grammar = $row1['grammar'];
$admin = $row1['admin'];
$pointcount = $row1['pointcount'];
$colourdis = $row1['colourdis'];
$ip = $_SERVER['REMOTE_ADDR'];
if(isset($_POST['msg'])) {
	$rawmsg3 = $_POST['msg'];
	$rawmsg2 = mysqli_real_escape_string($conn, $rawmsg3);
	$rawmsg = htmlentities($rawmsg2, ENT_QUOTES);
	$rawmsg = trim($rawmsg, "\n");
	$rawmsg = trim($rawmsg, "\r");
	$rawmsg = trim($rawmsg, " ");
}
else {
	$rawmsg = '';
}
include '../chat/emojis.php';
if ($grammar == 'no') {
include '../chat/no_grammar.php';
}
    function bb_parse($string) {
	$string = str_replace("\r\n", "\r", $string);
        $tags = 'b|i|s|u|rain|color|img'; 
        while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) { 
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]); 
            switch ($tag) { 
                case 'b': $replacement = "<b>$innertext</b>"; break; 
                case 'i': $replacement = "<i>$innertext</i>"; break; 
                case 's': $replacement = "<s>$innertext</s>"; break; 
                case 'u': $replacement = "<u>$innertext</u>"; break; 
		case 'rain': $replacement = "<span style=\"background-image: -webkit-gradient( linear, left top, right top, color-stop(0, #f22), color-stop(0.15, #f2f), color-stop(0.3, #22f), color-stop(0.45, #2ff), color-stop(0.6, #2f2),color-stop(0.75, #2f2), color-stop(0.9, #ff2), color-stop(1, #f22) );
background-image: gradient( linear, left top, right top, color-stop(0, #f22), color-stop(0.15, #f2f), color-stop(0.3, #22f), color-stop(0.45, #2ff), color-stop(0.6, #2f2),color-stop(0.75, #2f2), color-stop(0.9, #ff2), color-stop(1, #f22) );color:transparent;-webkit-background-clip: text;background-clip: text;\">$innertext</span>"; break;
                case 'color': $replacement = "<span style=\"color:".str_replace(array(':',';'),array('',''),$param).";\">$innertext</span>"; break;
                case 'img':
                    list($width, $height) = preg_split('`[Xx]`', $param); 
                    $replacement = "<img src=\"$innertext\" style=\"width:100%;\" onerror=\"imgError(this);\">";
                break;
				
            } 
            $string = str_replace($match, $replacement, $string); 
        } 
        return $string; 
    }
$msg2 = str_replace($in,$out,$rawmsg);
$msg = bb_parse($msg2);
$msgc = str_replace(array("\r"),'', $rawmsg);
$msgc = strlen(utf8_decode($msgc));
if($msg != '' && $msg != ' ' && $msg != "\n\r" && $disabled == '' && $sesschecktwo != '0' && $msgc <= 1000) {
$textchk = strlen($msg);
$insert = mysqli_query($conn, "INSERT INTO status_log (username, ip, colour, message, date, time) VALUES ('".$logged."','".$ip."','$colour','$msg','".date('d-m-y H:i:s')."','".time()."')");
$update = mysqli_query($conn, "UPDATE members SET postcount='$postcount'+1 WHERE username='$logged'");
if ($pointcount != 10) {
$update = mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'+1 WHERE username='$logged'");
}
else {
$update = mysqli_query($conn, "UPDATE members SET pointcount='0' WHERE username='$logged'");
$update = mysqli_query($conn, "UPDATE members SET points='$points'+1 WHERE username='$logged'");
}
}
// OH NO BRO.. COME ON.. DON'T GO USING 'HISTORY'
echo '<script>window.history.go(-1)</script>';
echo '<noscript><meta http-equiv="Refresh" content=\"0;url=/status"></noscript>';
die();
mysqli_close($conn);
?>
