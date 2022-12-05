<?php
if (!isset($_SESSION["loggedin"])) {
    header("location: login.php");
    exit;
}
$user = $_SESSION['username'];
function logEvent($ports, $serial_number)
{
    global $dbh;
    global $user;
    $dbh = openCon();
    $query = "INSERT INTO event_log(username, ports, serial_number) VALUES(:username, :xgs_ports, :serial_number)";
    $callToDb = $dbh->prepare($query);
    $callToDb->bindParam(":username", $user);
    $callToDb->bindParam(":xgs_ports", $ports);
    $callToDb->bindParam(":serial_number", $serial_number);
    $callToDb->execute();
}

function deleteEvents($serial_number) {
    global $dbh;
    $dbh = openCon();
    $query = "DELETE FROM event_log WHERE serial_number=:serial_number";
    $callToDb = $dbh->prepare($query);
    $callToDb->bindParam(":serial_number", $serial_number);
    $callToDb->execute();
}
