<?php
/*$HTTP_HOST = "pippo";
if($HTTP_HOST == "www.technomoto.it")
{
  $dbName = "technomoto_4072";
  $dbHost = "sql.technomoto.it";
  $dbUser = "technomoto_4072";
  $dbPassword = "tec3010";
}
else
{*/
$dbName = "salsiccia";
$dbHost = "localhost";
$dbUser = "salsiccia";
$dbPassword = "Salsiccia@123";
#}

$mysqli = mysqli_connect($dbHost, $dbUser, $dbPassword,$dbName)
or die(mysql_error());

?>

