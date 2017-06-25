<?php
# ON LOAD
if($rbe_token == '') {
	$value = 'Turned off'; // textbox
	$value2 = 'On'; // off
	$value3 = '#ff2424'; // status
	$value4 = ''; // tip
}
else {
	$value = $rbe_token;
	$value2 = 'Off';
	$value3 = '#00dd44';
	$value4 = 'Re-enable to change key';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SomeSN - RBEServ</title>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="/images/somesn.png">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>
<div id="title"><a href="/chat/roomsel.php"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">RBEServ</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<div id="content" style="text-align:center;">Allow and disallow remote biography editing.</div>
<div class="tab">Status</div>
<div id="content" style="margin-left:auto;margin-right:auto;text-align:center;">
<span class="tbesstat" style="background-color:<?php echo $value3; ?>;">&nbsp;</span>
<br>
<span class="rbetip" style="font-size:70%;"><?php echo $value4; ?></span>
</div>
<div class="tab">Key</div>
<div id="content" style="margin-left:auto;margin-right:auto;text-align:center;">
<form class="formtog" method="post" name="formtog" autocomplete="off">
<input type="text" name="info" class="info" value="<?php echo $value; ?>" readonly>
<div style="line-height:5px;">&nbsp;</div>
<input type="submit" class="tog mainbutton" value="<?php echo $value2; ?>">
</form>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</form>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script language="javascript" type="text/javascript">
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

$(document).ready(function () {
$('.formtog').submit(function () {
    $.post('/app/remotebioeditor/rbes_backend.php', $('.formtog').serialize(), function (data, textStatus) {
        $('.info').val('Generating token...');
		if(data == 'Turned off') {
			$('.info').val(data);
			$('.tog').val('On');
			$('.tbesstat').css({'background-color':'#ff2424'});
			$('.rbetip').html('');
		}
		else if(data != '') {
			$('.info').val(data);
			$('.tog').val('Off');
			$('.tbesstat').css({'background-color':'#00dd44'});
			$('.rbetip').html('Re-enable to change key');
		}
    });
    return false;
});
});
</script>
</body>
</html>
