<?php
$defaultLicencedPorts = 4;
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: login.php");
    exit;
}
$user = $_SESSION['username'];
function openCon()
{
    $username = "licuser";
    $password = "licpass";

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=licence_db', $username, $password);
        // set the PDO error mode to exception
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $dbh;
}

function separateLicencedPorts($total_ports)
{
    global $defaultLicencedPorts;
    return $total_ports - $defaultLicencedPorts;
}

function readAttrFromConfig($attrName)
{
    $config = json_decode(file_get_contents('./licencecfg.json', true));
    $cfgXgsPorts = $config->$attrName;
    return $cfgXgsPorts;
}

function calculateExpirationDate()
{    
    $dateStr = date("Y/m/d");
    $date = date_create_from_format('Y/m/d', $dateStr);
    $date->add(new DateInterval('P30D'));
    return $date->format("Y/m/d");
}
