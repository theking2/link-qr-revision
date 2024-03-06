<?php declare(strict_types=1);
namespace LinkQr;

$title    = 'Set password';
$messages = [];

const SEND_PWD_TEMPLATE = '<html>
<head>
<style type="text/css">
p {color:#1d1d1b;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:0.8em;}
h1{color:#ff9f35;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:1.3em;}
</style>
</head><body>
<h1>Kennwort geändert</h1>
<p>Das Kennwort für Benutzer %s wurde geändert.</p>
<p><a href="%s">Anmelden</a></p>
<p>Mit freundlichen Grüssen<br />
</body></html>';


define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
define( 'SETTINGS_FILE', ROOT . 'config/settings.ini' );
require ROOT . 'vendor/kingsoft/utils/settings.inc.php';
require ROOT . 'inc/session.inc.php';
require ROOT . 'vendor/autoload.php';

if( \Kingsoft\Utils\Html::check_request_params([ 'vc', 'username' ]) ) {
  header( "Location:." );
  trigger_error( "Missing parameters", E_USER_ERROR );
}
$uuid     = $_GET['vc'];
$username = $_GET['username'];

$user_email = UserEmail::find( where: [ 'uuid' => $uuid, 'username' => $username ] );
if( is_null( $user_email ) ) {
  $username   = $_GET['username'];
  $messages[] = sprintf( "User %s not found", $username );
  LOG->critical( "password reset fail, user not found", [ 'username'=> $username ] );
  header( 'Location:/logon/' );
  trigger_error( "User not found", E_USER_ERROR );
}

if( isset( $_POST['password'] ) ) {
  $user = User::find( where: [ 'username' => $username ] );
  if( is_null( $user ) ) {
    $user             = new User();
    $user->username   = $username;
    $user->vorname    = $user->nachname = "";
    $user->last_login = null;
  }
  try {
    $user->setPasswordHash( $_POST['password'] );
    // store hash of new password
    $user->freeze();
    // reset uuid for email
    $user_email->confirm_date = new \DateTime();
    $user_email->uuid         = null;
    $user_email->freeze();

    sendUpdateEmail();
    header( 'Location:/' );
    exit( 0 );

  } catch ( \Exception $e ) {
    LOG->critical( "password set user freeze failed". ['user'=> $user->username, 'error'=>$e->getMessage()] );
    $messages[] = "Fehler beim Password setzen. Bitte Administrator kontaktieren.{$e->getMessage()}";
  }
}
require ROOT . 'inc/header.inc.php'; ?>
<main>
  <dialog open>
    <h2>Kennwort zurücksetzen</h2>
    <form method='POST' id='form-container'>
      <label>Username</label>
      <input type="text" disabled value="<?= $user_email->username ?>">
      <label for='password'>Password</label>
      <div id="password-input">
        <input type='password' name='password' id='password' required minlength="5">
        <span id="show-toggle">🔒</span>
      </div>
      <div id="capslock-on">Feststelltaste aktiviert!</div>
      <div id="password-strength">
        <span id="poor"></span>
        <span id="weak"></span>
        <span id="strong"></span>
      </div>
      <div id="password-info"></div>
      <!--empty-->
      <p></p><input class='save-button' type='submit' value='OK'>
      </dl>
    </form>
    <?php
    if( $messages ) {
      foreach( $messages as $message ) {
        echo '<h2>' . $message . '</h2>';
      }
    }
    ?>
  </dialog>
</main>
</body>
<script defer src="/assets/password.js"></script>

</html>
<?php

/**
 * Send E-Mail update message
 */


function sendUpdateEmail()
{
  global $user_email;

  $to      = $user_email->email;
  $subject = "go321 Kennwort geändert";
  $headers = ''
    . 'From: ' . 'hostmaster@king.ma' . PHP_EOL
    . 'MIME-Version: 1.0' . PHP_EOL
    . 'Content-type: text/html; charset=utf-8' . PHP_EOL;
  $message = sprintf( SEND_PWD_TEMPLATE, $user_email->username, $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
  if( !DEBUG ) {
    mail( $to, $subject, $message, $headers );
  }
}
