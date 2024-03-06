<?php declare(strict_types=1);
define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define('SETTINGS_FILE', ROOT . 'config/settings.ini');

require ROOT . 'vendor/kingsoft/utils/settings.inc.php';
require ROOT . 'vendor/autoload.php';
require ROOT . 'inc/logger.inc.php';

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