<?php declare(strict_types=1);
namespace LinkQr;

define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define( 'SETTINGS_FILE', ROOT . 'config/settings.ini' );
require ROOT . 'vendor/kingsoft/utils/settings.inc.php';
require ROOT . 'inc/session.inc.php';
require ROOT . 'vendor/autoload.php';

$user = User::find(where: ['username'=> 'theking2']);
$user-> setPasswordHash('Qr4u2use');
$user-> freeze();
unset($user);

$user = User::find(where: ['username'=> 'theking2']);
if( $user-> checkPassword('Qr4u2use') ) {
  echo "Password is correct";
} else {
  echo "Password is incorrect";
}
$user-> last_login = new \DateTime();
$user-> freeze();
