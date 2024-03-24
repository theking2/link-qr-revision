<?php declare(strict_types=1);

define( 'SETTINGS_FILE', '../config/settings.ini' );
require '../vendor/kingsoft/utils/settings.inc.php';
require "../vendor/autoload.php";
$db = \Kingsoft\Db\Database::getConnection();
$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

function show_routine(string $type, string $name) {
  global $db;
  if( !is_dir($type) ) {
    mkdir($type);
  }
  $query = "SHOW CREATE $type `$name`";
  $stmt = $db->query( $query );
  foreach( $stmt as $row ) {
    echo "<h1>$name</h1>";
    if( is_null($row["Create $type"]) ) {
      echo "<p>Not found</p>";
      continue;
    }
    $source = $row["Create $type"];
    $source = preg_replace( "/(DEFINER=`\w*`@`\w*`)/", "/* $1 */", $source );
    echo "<pre>$source</pre>";
    $fh = fopen( "./$type/$name.sql", "w" );
    fwrite( $fh, "DROP $type IF EXISTS `$name`;\n");
    fwrite( $fh, "DELIMITER $$\n" );
    fwrite( $fh, $source );
    fwrite( $fh, "$$\nDELIMITER ;\n" );
    fclose( $fh );
  }
}

$stmt = $db->query("show procedure status where db = '".SETTINGS['db']['database']."'");
foreach( $stmt as $row ) {
  $name = $row["Name"];
  show_routine("Procedure", $name);
}

$stmt = $db->query("show function status where db = '".SETTINGS['db']['database']."'");
foreach( $stmt as $row) {
  $name = $row["Name"];
  show_routine("Function", $name);
}