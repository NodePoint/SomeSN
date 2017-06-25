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
$sessjack = $selectm = mysqli_query($conn, "SELECT username,password,colour,disabled,points,ip,priv,postcount,grammar,admin,kicked,pointcount,colourdis,flood FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	echo '<meta http-equiv="Refresh" content=\"0;url=/logout">';
	mysqli_close($conn);
	exit;
}
include('room.php'); // NO CHECKING IF THE FILE CAN BE ACCESSED

$multi = '';

$rowm = mysqli_fetch_assoc($selectm);
$colour = $rowm['colour'];
$disabled = $rowm['disabled'];
$points = $rowm['points'];
$usrip = $rowm['ip'];
$priv = $rowm['priv'];
$postcount = $rowm['postcount'];
$grammar = $rowm['grammar'];
$admin = $rowm['admin'];
$kicked = $rowm['kicked'];
$pointcount = $rowm['pointcount'];
$colourdis = $rowm['colourdis'];
$flood = $rowm['flood'];
$ip = $_SERVER['REMOTE_ADDR'];
$ua = $_SERVER['HTTP_USER_AGENT'];
$ua = mysqli_real_escape_string($conn, $ua);
$ua = htmlentities($ua, ENT_QUOTES);
if(isset($_POST['msg'])) {

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
if(IsTorExitPoint()) exit('proxy');
// DETECTION METHOD IS DEAD -- A DIFFERENT WORKING ONE EXISTS


    $rawmsg3 = $_POST['msg'];
	$rawmsg2 = mysqli_real_escape_string($conn, $rawmsg3);
	$rawmsg = htmlentities($rawmsg2, ENT_QUOTES);
}
else {
	$rawmsg = '';
}
$multi .= "UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged';";
include('emojis.php');
if($grammar == 'no') {
	include('no_grammar.php');
}
$valid = False;
$cmdpass = False;
    function bb_parse($string) {
	$string = str_replace("\r\n", "\r", $string);
        $tags = 'b|i|s|u|rain';
        while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) {
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
            switch ($tag) {
                case 'b': $replacement = "<b>$innertext</b>"; break;
                case 'i': $replacement = "<i>$innertext</i>"; break;
                case 's': $replacement = "<s>$innertext</s>"; break;
                case 'u': $replacement = "<u>$innertext</u>"; break;
		case 'rain': $replacement = "<span style=\"background-image: -webkit-gradient( linear, left top, right top, color-stop(0, #f22), color-stop(0.15, #f2f), color-stop(0.3, #22f), color-stop(0.45, #2ff), color-stop(0.6, #2f2),color-stop(0.75, #2f2), color-stop(0.9, #ff2), color-stop(1, #f22) );
background-image: gradient( linear, left top, right top, color-stop(0, #f22), color-stop(0.15, #f2f), color-stop(0.3, #22f), color-stop(0.45, #2ff), color-stop(0.6, #2f2),color-stop(0.75, #2f2), color-stop(0.9, #ff2), color-stop(1, #f22) );color:transparent;-webkit-background-clip: text;background-clip: text;\">$innertext</span>"; break;
            }
            $string = str_replace($match, $replacement, $string);
        }
        return $string;
    }
$msg5 = str_replace($in,$out,$rawmsg);
$msg4 = trim($msg5);
$msg3 = bb_parse($msg4);
$msg2 = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)?)@',
function($matches) {
	return '<a href="/safelink/?url='.str_replace('&amp;&amp;', '%26%26', $matches[1]).'" style="color:blue;text-decoration:none;" target="_blank">'.$matches[1].'</a>';
},
$msg3); // AUTOMATIC HYPERLINKING
$msg = preg_replace("/(|s)@(w*[A-Za-z0-9_]+w*)/", "$1<a href=\"/user/$2\">@$2</a>", $msg2); // @ HYPERLINKING TO PROFILES
$msgc = strlen(utf8_decode($rawmsg));

// ANTI-FLOOD SCRIPT START
$floodvalidation = 0; // validator
$floodd = 10; // flooded within this time frame (seconds)
$floodp = 2; // maximum flood posts allowed in (+1)
$floodpro = mysqli_query($conn, "SELECT sender,time FROM ".$roomtb." WHERE sender='$logged' AND time > UNIX_TIMESTAMP(NOW())-$floodd ORDER BY id DESC LIMIT $floodp,$floodp"); // query
$floodpro = mysqli_num_rows($floodpro); // get result count
if($floodpro > 0 && $msg != '' && $msg != ' ') {
	mysqli_query($conn, "UPDATE members SET flood='1' WHERE username='$logged'"); // prevent sending
	$floodvalidation = 1; // block send session
}
elseif($floodpro == 0 && $flood == 1 && $msg != '' && $msg != ' ') {
	mysqli_query($conn, "UPDATE members SET flood='0' WHERE username='$logged'"); // allow sending
	$floodvalidation = 0; // free send session
}
// SPAM SCRIPT END

if($msg == '' || $msg == ' ' || $disabled == 'yes' || $sesschecktwo == '0' || $kicked == 'yes' || $msgc > 1000 || $floodvalidation == 1) {
	$valid = False;
}
else {
	$valid = True;
}

if($valid === False) {
	mysqli_close($conn);
	exit('req');
}

$arr = explode(' ', $msg); // make into command

// WHISPER COMMAND
if($arr[0] == '/w' && $arr[1] != '' && $arr[2] != '') {
	$rawmsg = str_replace('/w '.$arr[1], '', $rawmsg);
	$rawmsg = str_replace($in,$out,$rawmsg);
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, recep, time) VALUES ('$logged','$logged','$ip','$colour','$rawmsg','$arr[1]',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// USERNAME COLOUR CHANGING COMMAND
if($arr[0] == '/colour' && $arr[1] != '') {
	$colourstrip = str_ireplace(array(';',':','\'','"',',','(',')'),'',$arr[1]);
	$colourstrip = substr($colourstrip,0,10).'';
	if($colourdis != 'yes') {
		$multi .= "UPDATE members SET colour='$colourstrip' WHERE username='$logged';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, colour, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:$colourstrip;\">$logged</span><span style=\"color:$colourstrip;\">Updated my colour!</span>','$colourstrip',UNIX_TIMESTAMP(NOW()));";
	}
	else {
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, message, colour, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red; font-weight: bold;\">$logged</span><span style=\"color:red;\">Failed changing their color! (Privilege revoked.)</span>','red',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// CLEAR CHAT COMMAND
if($arr[0] == '/clear' && $admin < 2) {
	$multi .= "TRUNCATE TABLE $roomtb;";
	$cmdpass = True;
}

// CLEAR STAFF (AUDIT) LOG COMMAND (NOT THE BEST OF IDEAS)
if($arr[0] == '/clstaff' && $admin == '1') {
	$multi .= 'TRUNCATE TABLE logger;';
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">Staff log was cleared.</span>',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// RANK MANAGEMENT COMMAND
if($arr[0] == '/user' && $arr[1] != '' && $admin < 3) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$rowcount = mysqli_num_rows($selectcom);
	$usercom = $rowcom['username'];
	if($rowcount == 0) {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to change {$arr[1]}\'s rank to user! (Reason: No such user.)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
		$multi .= "UPDATE members SET icon='blue',admin='5' WHERE username='$usercom';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, message, time) VALUES ('$logged','$logged','$ip','<span style=\"color:red;\">Changed $usercom\'s rank to user!</span>',UNIX_TIMESTAMP(NOW()));";
		$multi .= "INSERT INTO logger (user, userinvolved, activity, reason) VALUES ('$logged','$usercom','User privilege updated to user','[CHAT ACTION]');";
	}
	$cmdpass = True;
}

// RANK MANAGEMENT COMMAND
if($arr[0] == '/trial' && $arr[1] != '' && $admin < 3) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$rowcount = mysqli_num_rows($selectcom);
	if($rowcount == 0) {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) values ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to change {$arr[1]}\'s rank to trial! (Reason: No such user.)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
        $multi .= "UPDATE members SET icon='orange',admin='4' WHERE username='$usercom';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) values ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">Gave $usercom a trial rank!</span>',UNIX_TIMESTAMP(NOW()));";
		$multi .= "INSERT INTO logger (user, userinvolved, activity, reason) VALUES ('$logged','$usercom','User privilege updated to trial','[CHAT ACTION]');";
	}
	$cmdpass = True;
}

// RANK MANAGEMENT COMMAND
if($arr[0] == '/mod' && $arr[1] != '' && $admin < 3) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$rowcount = mysqli_num_rows($selectcom);
	if($rowcount == 0) {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to change {$arr[1]}\'s rank to moderator! (Reason: No such user)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
	   $multi .= "UPDATE members set icon='lime' WHERE username='$usercom';";
	   $multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">Promoted $usercom to a moderator rank!</span>',UNIX_TIMESTAMP(NOW()));";
	   $multi .= "INSERT INTO logger (user, userinvolved, activity, reason) VALUES ('$logged','$usercom','User privilege updated to moderator','[CHAT ACTION]');";
	}
	$cmdpass = True;
}

// RANK MANAGEMENT COMMAND
if($arr[0] == '/admin' && $arr[1] != '' && $admin < 3) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$rowcount = mysqli_num_rows($selectcom);
	if($rowcount == 0) {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to change {$arr[1]}\'s rank to administrator! (Reason: No such user)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
		$multi .= "UPDATE members SET icon='red',admin='2' WHERE username='$usercom';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">Promoted $usercom to an administrator rank!</span>',UNIX_TIMESTAMP(NOW()));";
		$multi .= "INSERT INTO logger (user, userinvolved, activity, reason) VALUES ('$logged','$usercom','User privilege updated to administrator','[CHAT ACTION]');";
	}
	$cmdpass = True;
}
// THIS WAS JUST TOO LAZY -- THE PLAN WAS TO ADD THIS SORT OF THING SOMEWHERE ELSE THAT IS MORE APPROPRIATE


// NOOBIFY COMMAND (EW)
if($arr[0] == '/noob' && $arr[1] != '' && $admin < 4) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$usrchkr = mysqli_num_rows($selectcom);
	if($usrchkr == '1') {
		if($arr[1] == 'Andre' && $logged != 'Andre') {
			$multi .= "UPDATE members SET grammar='no' WHERE username='$logged';";
			$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Cannot noob $usercom. It looks like that makes me the noob!</span>',UNIX_TIMESTAMP(NOW()));";
		}
		else {
			$multi .= "UPDATE members SET grammar='no' WHERE username='$arr[1]';";
			$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Noobed $usercom!</span>',UNIX_TIMESTAMP(NOW()));";
			$multi .= "INSERT INTO logger (user, userinvolved, activity, reason) VALUES ('$logged','$usercom','Noobed user','[CHAT ACTION]');";
		}
	}
	else {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to noob $arr[1]! (No such user.)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// UNDO NOOB COMMAND
if($arr[0] == '/unoob' && $arr[1] != '' && $admin < 4) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$usrchkr = mysqli_num_rows($selectcom);
	if($usrchkr == '1') {
		$multi .= "UPDATE members SET grammar='' WHERE username='$arr[1]';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Unnoobed $usercom!</span>',UNIX_TIMESTAMP(NOW()));";
		$multi .= "INSERT INTO logger (user, userinvolved, activity, reason) VALUES ('$logged','$usercom','Unnoobed user','[CHAT ACTION]');";
	}
    else {
        $multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to unnoob $arr[1]! (No such user.)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// CHANGING SOMEONE ELSE'S USERNAME COLOUR -- OWNER ONLY COMMAND
if($arr[0] == '/cc' && $arr[1] != '' && $arr[2] != '' && $admin == '1') {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$usrchkr = mysqli_num_rows($selectcom);
	if($usrchkr == '1') {
		$multi .= "UPDATE members SET colour='$arr[2]' WHERE username='$arr[1]';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">$logged has changed $usercom\'s color!</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">$logged has failed to change $arr[1]\'s color! (No such user.)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// AWAY COMMAND
if($arr[0] == '/away') {
	$rawmsg = str_replace("/away", "", $rawmsg);
	$rawmsg = trim($rawmsg);
	if($rawmsg != '' && $rawmsg != ' ') {
        $multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:grey;\">$logged is now away! (Reason: $rawmsg)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	if($arr[0] == '/away' && $rawmsg == '') {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:grey;\">$logged is now away!</span>',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// BACK COMMAND
if($arr[0] == '/back') {
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:grey;\">$logged is now back!</span>',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// DISPLAY POINTS COMMAND
if($arr[0] == '/bal') {
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:orange;\">I have $points points!</span>',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// USER KICK COMMAND
if($arr[0] == '/kick' && $arr[1] != '' && $admin < 3) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usercom = $rowcom['username'];
	$userexists = mysqli_num_rows($selectcom);
	if($userexists == 1) {
	if($usercom == 'Andre' && $logged != 'Andre') {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Cannot kick $usercom, action not permitted.</span>',UNIX_TIMESTAMP(NOW()));";
		$multi .= "INSERT INTO logger (sender, user, userinvolved, activity, reason) VALUES ('$logged','$logged','$usercom','Attempted kick','[CHAT ACTION]');";
	}
	else {
		$multi .= "UPDATE members SET kicked='yes' WHERE username='$arr[1]';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) values ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Kicked $usercom!</span>',UNIX_TIMESTAMP(NOW()));";
		$multi .= "INSERT INTO logger (sender, user, userinvolved, activity, reason) VALUES ('$logged','$logged','$usercom','Kicked user','[CHAT ACTION]');";
	}
}
else {
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;font-weight: bold;\">Cannot kick $usercom, action not permitted.</span>',UNIX_TIMESTAMP(NOW()));";
	$multi .= "INSERT INTO logger (sender, user, userinvolved, activity, reason) VALUES ('$logged','$logged','$usercom','Attempted kick (does not exist).','[CHAT ACTION]');";
}
	$cmdpass = True;
}

// ME COMMAND
if($arr[0] == '/me') {
	$rawmsg = str_replace("/me", "", $rawmsg);
	$rawmsg = trim($rawmsg);
	$rawmsg = str_replace($in,$out,$rawmsg);
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:$colour;\"><b>*$rawmsg*</b></span>',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// COLOUR PRIVS REVOKE COMMAND
if($arr[0] == '/coff' && $arr[1] != '' && $admin < 4) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usrchkr = mysqli_num_rows($selectcom);
	$usercom = $rowcom['username'];
	if($usrchkr == '1') {
		$multi .= "UPDATE members SET colourdis='yes', colour='gray' WHERE username='$usercom';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">$usercom has got their colour privilege revoked!</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
	   $multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red;\">Failed to remove $arr[1]\'s colour privilege! (No such user)-</span>',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// COLOUR PRIVS REINSTATE COMMAND
if($arr[0] == '/con' && $arr[1] != '' && $admin < 4) {
	$selectcom = mysqli_query($conn, "SELECT username FROM members WHERE username='$arr[1]'");
	$rowcom = mysqli_fetch_assoc($selectcom);
	$usrchkr = mysqli_num_rows($selectcom);
	$usercom = $rowcom['username'];
	if($usrchkr == '1') {
		$multi .= "UPDATE members SET colourdis='', colour='blue' WHERE username='$usercom';";
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:lime;\">$usercom has got their colour privilege restored!</span>',UNIX_TIMESTAMP(NOW()));";
	}
	else {
		$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:red; font-weight: bold;\">Failed to restore $arr[1]\'s colour privilege! (No such user)</span>',UNIX_TIMESTAMP(NOW()));";
	}
	$cmdpass = True;
}

// DISPLAY USER AGENT COMMAND -- CAN BE USED TO BYPASS A SERVER-SIDE CHARACTER LENGTH CHECK (IF IT EXISTS) SO REMOVE THIS
if($arr[0] == '/ua') {
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','$ua',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// WARNING COMMAND
if($arr[0] == '/warn' && $admin < 4) {
	$rawmsg = str_replace("/warn", '', $rawmsg);
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','<span style=\"color:white;background-color:red;padding:2px;padding-right:0px;\">$rawmsg</span>',UNIX_TIMESTAMP(NOW()));";
	$cmdpass = True;
}

// POINT MANAGEMENT
if(!$cmdpass) {
	$multi .= "INSERT INTO ".$roomtb." (sender, username, ip, colour, message, time) VALUES ('$logged','$logged','$ip','$colour','$msg',UNIX_TIMESTAMP(NOW()));";
	$multi .= "UPDATE members SET postcount='$postcount'+1 WHERE username='$logged';";
}

mysqli_multi_query($conn, $multi);

mysqli_close($conn);

// REDIRECT TO CHAT IF REQUEST HEADERS TYPICALLY FROM XHR AREN'T FOUND
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	header('location:/chat/?room='.$getroom);
	exit;
}
if($valid === True) {
	if($floodvalidation == 1) {
		echo 'flood';
	}
	else {
		echo 'ok';
	}
}
exit;

?>
