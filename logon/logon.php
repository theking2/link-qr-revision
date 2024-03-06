<?php declare(strict_types=1);
namespace LinkQr;

define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define( 'SETTINGS_FILE', ROOT . 'config/settings.ini' );
require ROOT . 'vendor/kingsoft/utils/settings.inc.php';
require ROOT . 'vendor/autoload.php';
require ROOT . 'inc/logger.inc.php';

require ROOT . 'inc/session.inc.php';

$script = $_POST['script'] ?? $_GET['s'] ?? '';
/**
 * do we have a logon attempt?
 */
if( !array_key_exists( 'username', $_POST ) or strlen( $_POST['username'] ) === 0 ) {
	// no logon attempt
	// redirect to login page
	LOG->notice( 'No username provided', $_REQUEST );
	header( "Location: ./" );
	exit;
}

if( isset( $_POST['action'] ) ) {
	LOG->debug( 'Logon attempt' );
	$user = User::find( where: [ 'username' => $_POST['username'] ] );

	if( $user and $user->checkPassword( $_POST['password'] ) ) {
		LOG->info( 'Logon successful', [ 'username' => $_POST['username'] ]	);
		// successful logon
		$user->last_login = new \DateTime();
		$user->freeze();
		$_SESSION['user_id']        = $user->id;
		$_SESSION['username']       = $user->username;
		$_SESSION['failed attempt'] = 0;
		header( "Location: /" );

	} else {
		// logon failed
		LOG->notice( 'Logon failed', [ 'username' => $_POST['username', 'attempt'=> $_SESSION['failed attempt']??0 ] );
		header( 'Location: ./' );
		error_log( '[' . date( 'Y-m-d H:i:s' ) . '] ' . __FILE__ . ':' . __LINE__ . ':Logon error for user ' . $_POST['username'] );
		exit();
	}

} else {
	header( "Location: /logon" );
}
