<?php declare(strict_types=1);
define( 'SETTINGS_FILE', '../config/settings.ini' );
require '../vendor/kingsoft/utils/settings.inc.php';
require '../vendor/autoload.php';
require '../inc/logger.inc.php';
// Allow these characters
$charset ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNAOPQRSTUVWXYZ1234567890-_.!~*'()";

function getCode(): string {
  global $charset;
  $length = strlen($charset)-1;

  // short code is 5 characters. When changed, also change the database!
  $result
	= $charset[rand(0,$length)]
 	. $charset[rand(0,$length)]
	. $charset[rand(0,$length)]
	. $charset[rand(0,$length)]
	. $charset[rand(0,$length)]
	;
  return $result;
}

$insert = \Kingsoft\Db\Database::getConnection()-> prepare("
insert into
    code( code,  url,  last_used, hits)
  values(:code, null, '0000-00-00', default)");
$code = getCode();
$insert->bindParam(':code',$code);

for($i=1000; $i>0; $i--) {
  $code = getCode();
  LOG->debug("Adding code $code");
  $insert-> execute() or trigger_error($insert->errorInfo()[2], E_USER_ERROR);
}
echo 'codes created';
