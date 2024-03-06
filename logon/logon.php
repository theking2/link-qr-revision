<?php declare(strict_types=1);
namespace LinkQr;

define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define( 'SETTINGS_FILE', ROOT . 'config/settings.ini' );
require ROOT . 'inc/session.inc.php';
require ROOT . 'vendor/kingsoft/utils/settings.inc.php';
require ROOT . 'vendor/autoload.php';

$script = $_POST['script']??$_GET['s']??'';
/**
 * do we have a logon attempt?
 */
if( !array_key_exists('username', $_POST) or strlen($_POST['username']) === 0 ) {
	// no logon attempt
	// redirect to login page
	header("Location: ./");
	exit;
}

if( isset($_POST['action']) ) {
	$user = User::find(where:['username'=> $_POST['username']]);

	if( $user and $user-> checkPassword($_POST['password']) ) {
		// successful logon
    $user-> last_login = new \DateTime();
    $user-> freeze();
		$_SESSION['user_id'] = $user-> id;
		$_SESSION['username'] = $user-> username;
		$_SESSION['failed attempt'] = 0;
   	header( "Location: /" );
		
  } else {
    header('Location: ./' );
    error_log('['.date('Y-m-d H:i:s').'] '.__FILE__.':'.__LINE__.':Logon error for user '.$_POST['username']);
    exit();
  }

} else {
	header( "Location: /logon" );
}
