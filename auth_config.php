<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$mysql_ip = $_ENV["DB_ADDRESSs"];

define('DB_SERVER', $mysql_ip);
define('DB_USERNAME', 'licuser');
define('DB_PASSWORD', 'licpass');
define('DB_NAME', 'licence_db');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
