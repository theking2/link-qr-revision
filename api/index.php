<?php declare(strict_types=1);
define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define('SETTINGS_FILE', ROOT . 'config/settings.ini');
require ROOT . 'vendor/kingsoft/utils/settings.inc.php';
require ROOT . 'vendor/autoload.php';
require ROOT . 'inc/logger.inc.php';

require ROOT . 'inc/session.inc.php';
if( !isset( $_SESSION['user'] ) ) {
  http_response_code( 403 );
  trigger_error( 'Unauthorized', E_USER_ERROR );
}

use Kingsoft\PersistRest\{PersistRest, PersistRequest};
use Kingsoft\Http\{StatusCode, Response};

try {
  $request = new PersistRequest(
    SETTINGS['api']['allowedendpoints'],
    SETTINGS['api']['allowedmethods'],
    SETTINGS['api']['allowedorigin'],
    (int) SETTINGS['api']['maxage'],
    LOG
  );
  $api = new PersistRest( $request );
} catch ( Exception $e ) {
  Response::sendError( $e->getMessage(), StatusCode::InternalServerError->value );
}