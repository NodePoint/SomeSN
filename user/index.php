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

if(isset($_GET['user'])) {
	$user = $_GET['user'];
	$user = mysqli_real_escape_string($conn, $user);
	$user = htmlentities($user, ENT_QUOTES);
}
else {
	$user = '';
}
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,admin,token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$select1 = mysqli_query($conn, "SELECT username,admin,id,disabled,profile,points,avatar,grouptype,website,postcount,time,icon,verification FROM members WHERE username='$user' COLLATE utf8mb4_general_ci");
$row1 = mysqli_fetch_assoc($select1);
$userc = mysqli_num_rows($select1);
$admin = $row['admin'];
$usern = $row1['username'];
$adminpro = $row1['admin'];
$id = $row1['id'];
$disabled = $row['disabled'];
$token = $row['token'];
$disabledpro = $row1['disabled'];
$profile = $row1['profile'];
$pointspro = $row1['points'];
$avatar = $row1['avatar'];
$grouptype = $row1['grouptype'];
$website = $row1['website'];
$postcount = $row1['postcount'];
$time = $row1['time'];
$icon = $row1['icon'];
$verification = $row1['verification'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");

if($_SERVER['REQUEST_URI'] == '/user/?user='.$user) {
	header('location: /user/'.$user);
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/images/somesn.png">
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Profile</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<?php
// YOU'VE GOT TO BE OUT OF YOUR MIND TO THINK THAT I WOULD FURTHER COMMENT ON THIS
if (!isset($_GET['user'])) {
	echo '<title>SomeSN - Profile</title>';
	echo '<div id="content" style="text-align:center;padding:0px;">';
	echo '<div id="info">Logged in as '.$logged.'</div>';
	echo '<br>';
	echo '<font color="lime" size="4">Enter a username below that you want to visit.</font>';
	echo '<br>';
	echo '<br>';
	echo '</div>';
}
if (isset($_GET['user']) && $user == '') {
	echo '<title>SomeSN - User blank</title>';
	echo '<div id="content" style="text-align:center;padding:0px;">';
	echo '<div id="info">Logged in as '.$logged.'</div>';
	echo '<br>';
	echo '<font color="red">The UID is blank, please specify one.</font>';
	echo '<br>';
	echo '<br>';
	echo '</div>';
}
if (isset($_GET['user']) && $user != '' && $userc == '0') {
	echo '<title>SomeSN - No such user</title>';
	echo '<div id="content" style="text-align:center;padding:0px;">';
	echo '<div id="info" style="border-radius:5px 5px 0px 0px;">Logged in as '.$logged.'</div>';
	echo '<br>';
	echo '<font color="red">User doesn\'t exist!</font>';
	echo '<br>';
	echo '<br>';
	echo '</div>';
}
if (isset($_GET['user']) && $user != '' && $user != ' ' && $userc == '1' && $disabledpro == 'yes') {
	echo '<title>SomeSN - '.$user.'\'s Suspended</title>';
	echo '<div id="content" style="text-align:center;padding:0px;">';
	echo '<div id="info" style="border-radius:5px 5px 0px 0px;">Logged in as '.$logged.'</div>';
	echo '<br>';
	echo '<font color="red">This account is currently suspended.</font>';
	echo '<br>';
	echo '<br>';
	echo '</div>';
}
if (isset($_GET['user']) && $user != '' && $user != ' ' && $userc == '1' && $disabledpro != 'yes') {
	echo '<title>SomeSN - '.$usern.'\'s Profile</title>';
	echo '<div id="results"></div>';
	function ago($time)
	{
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
	$seconds = 60;
	$online = mysqli_query($conn, "SELECT username,time FROM members WHERE username='$user' COLLATE utf8mb4_general_ci AND time > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY username");
	$online = mysqli_num_rows($online);
	echo '<div id="content" style="text-align:center;padding:0px;">';
	echo '<div style="border:0px solid;background-color:#2090ff;max-width:100%;padding:6px;">';
	echo '<div style="width:80%;text-align:left;float:left;color:white;font-weight:bolder;line-height:-4px;">'.$usern.'</div>';
	if($online == 1) {
		echo '<div style="width:20%;text-align:right;float:right;color:white;font-size:15px;height:0.4%;"><span id="usrs" style="color:lime;font-weight:bolder;">&#9679;</span>&nbsp;Online&nbsp;</div>';
	}
	else {
		echo '<div style="width:20%;text-align:right;float:right;color:white;font-size:15px;height:0.4%;"><span id="usrs" style="color:red;font-weight:bolder;">&#9679;</span>&nbsp;Offline&nbsp;<span style="font-size:10px;">';
		if ($time != '') {
			echo ago($time);
			echo '&nbsp;</span></div>';
		}
	else {
		echo 'Unknown';
		echo '&nbsp;</span></div>';
	}
	}
	echo '<br>';
	echo '</div>';
	if($verification == 1) {
		echo '<div id="avatarmain" style="padding-left: 10px;">';
	}
	else {
		echo '<div id="avatarmain">';
	}
	echo '<img id="avatarprofile" src="'.$avatar.'" onerror="imgError(this);" style="border-color:' . $icon . ';">';
	if($verification == 1) {
		echo '<span class="verification">&#10003;</span>';
	}
	echo '</div>';
	echo '<div id="bottomprobar">';
	echo '<span id="bpbb" onclick="alert(\'This feature is not implemented yet.\')">&nbsp;PM '.$usern.'&nbsp;</span>';
	if ($logged == $usern) {
		echo '&nbsp;&nbsp;<span id="bpbb" onclick="location.href=\'/account\'">&nbsp;Edit&nbsp;</span>';
	}
	echo '</div>';
	echo '<div class="tab">About</div>';
	echo '<div id="aboutboxy">';
	echo 'User ID: '.$id;
	echo '<br>';
	echo 'Position: ';
	switch($adminpro) {
		case 1:
			echo 'Owner';
			break;
		case 2:
			echo 'Administrator';
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
	}
	echo '<br>';
	echo 'DroidPoints: '.$pointspro;
	echo '<br>';
	echo 'Postcount: '.$postcount;
	echo '<br>';
	echo 'Group: ';
	if($grouptype != '' && $grouptype != ' ') {
		echo $grouptype;
	}
	else {
		echo 'No group';
	}
	if($website != '' && $website != ' ') {
		echo '<br>';
		echo 'Website:<br>';
		echo '<a href="/safelink/?url='.$website.'" style="color:blue;" target="_blank">'.$website.'</a>';
	}
	echo '</div>';
	echo '<div class="tab">Biography</div>';
	echo '<div id="bioboxy">';
	if($profile != '' && $profile != ' ') {
		function bb_parse($string) {
		$string = str_replace("\r\n", "\r", $string);
		$tags = 'b|i|s|style|rain|size|color|center|quote|url|img'; 
        while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) { 
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]); 
            switch ($tag) { 
                case 'b': $replacement = "<b>$innertext</b>"; break; 
                case 'i': $replacement = "<i>$innertext</i>"; break;
                case 's': $replacement = "<s>$innertext</s>"; break;
                case 'style': $replacement = "<style>".str_replace("\r","",html_entity_decode($innertext))."</style>"; $styled = True; break;
		case 'rain': $replacement = "<span style=\"background-image: -webkit-gradient( linear, left top, right top, color-stop(0, #f22), color-stop(0.15, #f2f), color-stop(0.3, #22f), color-stop(0.45, #2ff), color-stop(0.6, #2f2),color-stop(0.75, #2f2), color-stop(0.9, #ff2), color-stop(1, #f22) );
background-image: gradient( linear, left top, right top, color-stop(0, #f22), color-stop(0.15, #f2f), color-stop(0.3, #22f), color-stop(0.45, #2ff), color-stop(0.6, #2f2),color-stop(0.75, #2f2), color-stop(0.9, #ff2), color-stop(1, #f22) );color:transparent;-webkit-background-clip: text;background-clip: text;\">$innertext</span>"; break;
                case 'size': $replacement = "<span style=\"font-size: ".str_replace(array(':',';','{','}'),array('','','',''),$param).";\">$innertext</span>"; break; 
                case 'color': $replacement = "<span style=\"color: ".str_replace(array(':',';','{','}','\"'),array('','','','',''),$param).";\">$innertext</span>"; break; 
                case 'center': $replacement = "<div id=\"centered\">$innertext</div>"; break;
                case 'quote': $replacement = "<blockquote>$innertext</blockquote>" . $param? "<cite>$param</cite>" : ''; break; 
                case 'url': $replacement = '<a href="' . ($param? $param : $innertext) . "\" style=\"color:blue;\" target=\"_blank\">$innertext</a>"; break; 
                case 'img':
                    list($width, $height) = preg_split('`[Xx]`', $param); 
                    $replacement = "<img src=\"$innertext\" style=\"width:100%;\" onerror=\"imgError(this);\">";
                break;
            } 
            $string = str_replace($match, $replacement, $string); 
        } 
        return $string; 
    }
	include '../chat/emojis.php';
	$profile = str_replace($in,$out,$profile);
	$profile = bb_parse($profile);
	$profile = str_replace("</style>\r",'</style>',$profile);
	$profile = nl2br($profile);
	$styleonly = preg_replace('/\<style\>.+?\<\/style\>/sm', '', $profile);
	if($styleonly == '') {
		$profileo = 'This user hasn\'t filled in their biography.';
	}
	else {
		$profileo = '<div style="text-align:left;">'.$profile.'</div>';
	}
	}
	else {
		$profileo = 'This user hasn\'t filled in their biography.';
	}
	echo $profileo;
	echo '</div>';
	echo '</div>';
	$idgetchk = mysqli_query($conn, "SELECT * FROM profile_comments WHERE userprofile='$user' COLLATE utf8mb4_general_ci ORDER BY id DESC");
	$stc = mysqli_num_rows($idgetchk);
	if ($stc == 0) {
		echo '<div class="tab">Comments <span id="circleind">'.$stc.'</span></div>';
		echo '<div id="procomments">';
		echo '<br>Be the first one to comment.<br><br>';
		echo '</div>';
		echo '</div>';
	}
	else {
		if($stc > 99) {
			echo '<div class="tab">Comments <span id="circleind">+</span></div>';
		}
		else {
			echo '<div class="tab">Comments <span id="circleind">'.$stc.'</span></div>';
		}
		echo '<div id="procomments">';
		echo '<br>';
		while($msgs = mysqli_fetch_assoc($idgetchk)) {
			$idc = $msgs['id'];
			$uidc = $msgs['uid'];
			$username = $msgs['user'];
			$message = $msgs['message'];
			$ipaddr = $msgs['ip'];
			$colourc = $msgs['colour'];
			$lcount = mysqli_query($conn, "SELECT * FROM status_likes WHERE (username='$username' AND comnt='1')");
			$lcount = mysqli_num_rows($lcount);
			$date = $msgs['date'];
			$time = $msgs['time'];
			$ts = $msgs['timestamp'];
			$select2 = mysqli_query($conn, "SELECT * FROM members WHERE username='$username'");
			$row3 = mysqli_fetch_assoc($select2);
			$rankmsg = $row3['admin'];
			$avatars = $row3['avatar'];
			$icon = $row3['icon'];
			if ($admin == 1 && $username != $logged) {
				echo '<div id="statuses" style="width:90%;"><img id="procom" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Are you sure you want you want to delete this comment?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&suser='.$user.'&&token='.$token.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
			if ($rankmsg == 1) {
				echo 'Owner';
			}
			if ($rankmsg == 2) {
				echo 'Admin';
			}
			if ($rankmsg == 3) {
				echo 'Mod';
			}
			if ($rankmsg == 4) {
				echo 'Trial';
			}
			if ($rankmsg == 5) {
				echo 'User';
			}
			echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#6eff5d;color:white;border:0px solid;border-radius:0px 0px 0px 5px;width:49%;display:inline-block;text-align:center;padding:0.5%;" onclick="alert(\'This feature is not implemented yet.\');">Likes ('.$lcount.')</span><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 0px;display:inline-block;width:49%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
			}
			else {
				if ($admin == 1 && $username == $logged) {
					echo '<div id="statuses" style="width:90%;"><img id="procom" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Are you sure you want you want to delete this comment?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&suser='.$user.'&&token='.$token.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
				if ($rankmsg == 1) {
					echo 'Owner';
				}
				if ($rankmsg == 2) {
					echo 'Admin';
				}
				if ($rankmsg == 3) {
					echo 'Mod';
				}
				if ($rankmsg == 4) {
					echo 'Trial';
				}
				if ($rankmsg == 5) {
					echo 'User';
				}
				echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 5px;display:inline-block;width:99%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
				}
				if ($admin < 4 && $admin != 1 && $username != $logged) {
					echo '<div id="statuses" style="width:90%;"><img id="procom" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Are you sure you want you want to delete this comment?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&suser='.$user.'&&token='.$token.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
				if ($rankmsg == 1) {
					echo 'Owner';
				}
				if ($rankmsg == 2) {
					echo 'Admin';
				}
				if ($rankmsg == 3) {
					echo 'Mod';
				}
				if ($rankmsg == 4) {
					echo 'Trial';
				}
				if ($rankmsg == 5) {
					echo 'User';
				}
				echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 5px;display:inline-block;width:99%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
				}
				else {
					if ($admin < 4 && $admin != 1 && $username == $logged) {
						echo '<div id="statuses" style="width:90%;"><img id="procom" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Are you sure you want you want to delete this comment?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&suser='.$user.'&&token='.$token.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
						if ($rankmsg == 1) {
							echo 'Owner';
						}
						if ($rankmsg == 2) {
							echo 'Admin';
						}
						if ($rankmsg == 3) {
							echo 'Mod';
						}
						if ($rankmsg == 4) {
							echo 'Trial';
						}
						if ($rankmsg == 5) {
							echo 'User';
						}
						echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 5px;display:inline-block;width:99%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
						}
						if ($username != $logged && $admin > 3) {
							echo '<div id="statuses" style="width:90%;"><img id="procom" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';">&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
						if ($rankmsg == 1) {
							echo 'Owner';
						}
						if ($rankmsg == 2) {
							echo 'Admin';
						}
						if ($rankmsg == 3) {
							echo 'Mod';
						}
						if ($rankmsg == 4) {
							echo 'Trial';
						}
						if ($rankmsg == 5) {
							echo 'User';
						}
						echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 5px;display:inline-block;width:99%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
						}
						else {
							if ($username == $logged && $admin > 3) {
								echo '<div id="statuses" style="width:90%;"><img id="procom" src="'.$avatars.'" onerror="imgError(this);" width="60px" height="60px" style="border-color:' . $icon . ';"><span style="display:inline-block;float:right;margin:auto;text-align:center;background-color:#ff3737;color:white;font-weight:bold;border:0px solid;border-radius:0px 5px 0px 0px;padding-top:1px;" onclick="var conf=confirm(\'Are you sure you want you want to delete this comment?\'); if (conf==true){window.location = \'delcom.php?id='.$idc.'&&suser='.$user.'&&token='.$token.'\';}">&nbsp;&#10006;&nbsp;</span>&nbsp;&nbsp;<span style="display:inline-block;margin:auto;"><span style="font-weight: bold;font-size:20px;color:'.$colourc.';cursor:pointer;" onclick="location.href=\'/user/' . $username . '\'">' . $username . '</span>&nbsp;</span>-&nbsp;';
								if ($rankmsg == 1) {
									echo 'Owner';
								}
								if ($rankmsg == 2) {
									echo 'Admin';
								}
								if ($rankmsg == 3) {
									echo 'Mod';
								}
								if ($rankmsg == 4) {
									echo 'Trial';
								}
								if ($rankmsg == 5) {
									echo 'User';
								}
								echo '&nbsp;-&nbsp;<span onclick="alert(\'User: '.$username.'\nTimestamp Information: '.$date.'\');">'.ago($time).'</span><hr><div id="statusmsg">' . nl2br(trim($message,"\n\r")) .'</div><span style="background-color:#ff3737;color:white;border:0px solid;border-radius:0px 0px 5px 5px;display:inline-block;width:99%;text-align:center;padding:0.5%; onclick="alert(\'This feature is not implemented yet.\');">Report</span><br></div><br>';
							}
						}
					}
				}
			}
		}
		echo '<form id="sendbutton" method="post" action="sub-comment.php?user='.$user.'" autocomplete="off">';
		echo '<div id="textboxmsg">';
		echo '<div id="searchcontainer">';
		echo '<textarea name="msg" type="text" class="textfield" maxlength="1000" placeholder="Enter your comment..." style="resize:none;height:100px;" value=""></textarea>';
		echo '<div style="line-height:5px;">&nbsp;</div>';
		echo '<button type="submit" class="mainbutton">Post</button>';
		echo '<div style="line-height:8px;">&nbsp;</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	if (isset($_GET['user']) && $user != '' && $user != ' ' && $userc == '1' && $disabledpro == '') {
		echo '<div id="content" style="text-align:center;padding:0px;">';
		echo '<div class="tab">Search for user</div>';
	}
else {
	echo '<div id="content" style="text-align:center;padding:0px;padding-top:6px;">';
}
?>
<form method="post" action="var/" class="searchform" name="userform" autocomplete="off">
<div style="line-height:5px;">&nbsp;</div>
<input type="text" onkeyup="getUserlist(this.value)" name="user" placeholder="Enter username">
<div style="line-height:5px;">&nbsp;</div>
</form>
</div>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script type="text/javascript">
	function imgError(image) {
		image.onerror = "";
		image.src = "/images/bimg.png";
		return true;
	}
    function getUserlist(value) {
    $.post("search.php", {q:value},function(data){
        $("#results").html(data);
    }
    ); 
    }
$('.searchform').submit(function () {
return false;
});
// MENU CODE START
$(document).ready(function () {
    $('.menu').on('tap', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	$('#menulist').stop(true,false).slideToggle(350);
	updateMenu();
    })
});
function updateMenu()
{
var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp = new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange = function()
{
if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
{
document.getElementById("menulist").innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET","/elements/menu.php",true);
xmlhttp.send(null);
}
// MENU CODE END
</script>
</body>
</html>
