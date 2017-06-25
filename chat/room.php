<?php
if(isset($_GET['room'])) {
	$getroom = $_GET['room'];
	$getroom = strtolower($getroom);
}
else {
	$getroom = '';
}

// HARDCODED CHATROOMS -- $ROOMTB IS SUPPOSED TO BE THE TABLE AND $ROOMOL IS THE COLUMN WHERE THE ONLINE TIME WOULD BE STORED IN THE MEMBERS TABLE

switch($getroom) {
	case 'orig':
		$roomname = 'Original';
		$roomtb = 'chat_original';
		$roomol = 'origchattime';
		$roomurl = '/chat/?room=orig';
		break;
	case 'rp':
		$roomname = 'Roleplay';
		$roomtb = 'chat_rp';
		$roomol = 'rpchattime';
		$roomurl = '/chat/?room=rp';
		break;
	case 'staff':
		if($admin > 3) {
			header('location: /chat/?room=orig');
			// MISSING EXIT
		}
		$roomname = 'Staff';
		$roomtb = 'chat_staff';
		$roomol = 'staffchattime';
		$roomurl = '/chat/?room=staff';
		break;
	case 'dev':
		$roomname = 'Developers';
		$roomtb = 'chat_dev';
		$roomol = 'devchattime';
		$roomurl = '/chat/?room=dev';
		break;
	default:
		$roomname = 'Original';
		$roomtb = 'chat_original';
		$roomol = 'origchattime';
		$roomurl = '/chat/?room=orig';
}
?>
