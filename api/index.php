<?php declare(strict_types=1);
define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define( 'SETTINGS_FILE', ROOT . 'config/settings.ini' );
require ROOT . 'vendor/kingsoft/utils/settings.inc.php';

require ROOT . 'vendor/autoload.php';
require ROOT . 'inc/logger.inc.php';
require ROOT . 'inc/session.inc.php';

use Kingsoft\Http\{StatusCode, Rest, Request, Response, ContentType};

if( !isset( $_SESSION['username'] ) ) {
  http_response_code( 403 );
  trigger_error( 'Unauthorized', E_USER_ERROR );
}
class CodeRest extends Rest
{

  /**
   *
   * @param Throwable $e
   * @return string
   */
  protected function createExceptionBody( Throwable $e ): string
  {
    return parent::createExceptionBody( $e );
  }

  /**
   * @return void
   */
  function delete(): void
  {
    throw new \Exception( 'Not implemented' );
  }

  /**
   * @return void
   */
  function get(): void
  {
    throw new \Exception( 'Not implemented' );
  }

  /**
   * @return string
   */
  protected function getNamespace(): string
  {
    return 'LinkQr';
  }

  /**
   * @return void
   */
  function head(): void
  {
    throw new \Exception( 'Not implemented' );
  }

  /**
   * @return void
   */
  function post(): void
  {
    throw new \Exception( 'Not implemented' );
  }

  /**
   * @return void
   */
  function put(): void
  {
    /** @var \Kingsoft\Persist\Base $resourceObject */
    if( $resourceObject = $this->getResource() ) {

      $input = json_decode( file_get_contents( 'php://input' ), true );
      $resourceObject->setFromArray( $input );

      if( $result = $resourceObject->freeze() ) {
        Response::sendStatusCode( StatusCode::OK );
        $payload = [ 'id' => $resourceObject->getKeyValue(), 'result' => $result ];
        Response::sendPayLoad( $payload, [ $resourceObject, "getStateHash" ] );

      }

      LOG->info( "Error in put", [ 'payload' => $input ] );

      Response::sendStatusCode( StatusCode::InternalServerError );
      Response::sendMessage( 'error', 0, 'Internal error' );

    }
  }
  /**
   * Get a resource by id
   * @return \Kingsoft\Persist\Base
   * @throws \Exception, \InvalidArgumentException, \Kingsoft\DB\DatabaseException, \Kingsoft\Persist\RecordNotFoundException
   * side effect: sends a response and exits if the resource is not found
   */
  protected function getResource(): ?\Kingsoft\Persist\Base
  {
    if( !isset( $this->request->id ) ) {
      LOG->info( "Id not set", [ 'ressource' => $this->request->resource ] );
      Response::sendStatusCode( StatusCode::BadRequest );
      Response::sendMessage( 'error', 0, 'No id provided' );
      trigger_error( "no id" );
    }
    if( $resourceObject = new $this->resource_handler( $this->request->id ) ) {
      return $resourceObject;

    } else {
      LOG->info( "Not found", [ 'ressource' => $this->request->resource, 'id' => $this->request->id ?? '' ] );

      Response::sendStatusCode( StatusCode::NotFound );
      Response::sendContentType( ContentType::Json );
      $payload = [ 
        'result' => 'error',
        'message' => 'Resource not found',
        'resource' => $this->request->resource,
        'id' => $this->request->id ?? '?',
      ];
      Response::sendPayload( $payload );
      return null;
    }
  }
}

try {
  $request  = new Request( [ 'Code' ], 'PUT', '*', null, LOG );
  $response = new CodeRest( $request );
} catch ( Exception $e ) {
  Response::sendError( $e->getMessage(), StatusCode::InternalServerError->value );
}