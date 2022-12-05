<?php
include "common.php";
include "logger.php";

$licenceId = $_GET['licence_id'];
$serial_number = $_GET['serial_number'];

$dbh = openCon();

function removeLicence()
{
    global $dbh;
    global $licenceId;
    global $serial_number;
    $query = "DELETE FROM licence_data WHERE licence_id=:licenceId;";
    $callToDb = $dbh->prepare($query);
    $callToDb->bindParam(":licenceId", $licenceId);
    if (!$callToDb->execute()) {
        return '<h3 style="text-align:center;">Error!</h3>';
    }
    deleteEvents($serial_number);
}

removeLicence();
header('Location: index.php');