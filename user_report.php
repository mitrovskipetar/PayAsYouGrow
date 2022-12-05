<?php
include 'common.php';

$username = $_GET['username'];
//Check if user is logged in, if not redirect to login page
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: login.php");
    exit;
}

$sessionUser = $_SESSION['username'];
$dbh = openCon();
$limit = 10;
$sql  = "SELECT count(*) FROM event_log WHERE username=:username";
$res = $dbh->prepare($sql);
$res->bindParam(':username', $username, PDO::PARAM_STR);
$res->execute();
$total_results = $res->fetchColumn();
$total_pages = ceil($total_results / $limit);
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$starting_limit = ($page - 1) * $limit;

// Fetching event log data for the table
$show  = "SELECT * FROM event_log WHERE username=:username ORDER BY date DESC LIMIT :start, :rows";
$r = $dbh->prepare($show);
$r->bindParam(':username', $username, PDO::PARAM_STR);
$r->bindParam(':start', $starting_limit, PDO::PARAM_INT);
$r->bindParam(':rows', $limit, PDO::PARAM_INT);
$r->execute();
$eventLogs = $r->fetchAll(PDO::FETCH_OBJ);

//Count XGSPON licences for User in order to calculate total ports
$tempq  = "SELECT count(*) FROM licence_data WHERE user=:user";
$re = $dbh->prepare($tempq);
$re->bindParam(':user', $username, PDO::PARAM_STR);
$re->execute();
$xgsponPortsCount = $re->fetchColumn();

//Get sum of XGSPON ports for total calculation
$totalXgsponPorts = 0;
$tempq  = "SELECT SUM(xgs_ports) FROM licence_data WHERE user=:user";
$re = $dbh->prepare($tempq);
$re->bindParam(':user', $username, PDO::PARAM_STR);
$re->execute();
$totalXgsponPorts = $re->fetchColumn();
$totalLicencedPorts = $totalXgsponPorts;

// Count all ports
$query  = "SELECT * FROM licence_data WHERE user=:user ORDER BY licence_id";
$result = $dbh->prepare($query);
$result->bindParam(':user', $username, PDO::PARAM_STR);
$result->execute();
$resultTable = $result->fetchAll(PDO::FETCH_OBJ);

foreach ($resultTable as $obj) {
    $total_ports += $obj->xgs_ports;
}
?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>
        User Report:
    </title>
</head>

<body>
    <a href="index.php">
        <img id="logo_image" src="iskratel-logo.png" />
    </a>
    <div class="container-table">
        <table class="table">
            <thead style="border-style : hidden!important;">
                <th scope="col" class="col-md-2">Report generated for "<?php echo $username ?>"</th>
            </thead>
            <thead>
                <tr style="border-bottom-style : hidden!important;">
                    <th scope="col" class="col-md-2"> XGS Ports Added</th>
                    <th scope="col" class="col-md-2"> &#931 Licenced XGS Ports</th>
                    <th scope="col" class="col-md-2">Serial Number</th>
                    <th scope="col" class="col-md-2">Date</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventLogs as $log) {

                ?>
                    <tr>
                        <td class="col-md-2"><?= $log->ports ?></td>
                        <td class="col-md-2"><?= $totalLicencedPorts ?></td>
                        <td class="col-md-2"><?= $log->serial_number ?></td>
                        <td class="col-md-2"><?= $log->date ?></td>

                    </tr>
                <?php
                    $totalLicencedPorts = $totalLicencedPorts - $log->ports;
                }
                ?>
            </tbody>
        </table>
        <div>
            <input type="button" class="btn btn-secondary ml-2" style="position:absolute; bottom:7%; left:5%;" onclick="location.href = 'usage_overview.php';" value="Return to all users">
        </div>
        <div style="align-items: center;text-align: center;position:absolute; bottom:5%; left:47%;">
            <ul class="pagination" style="margin-left:48%;">
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <li class="page-item"><a href='<?php echo "?username=$username&page=$page"; ?>' class="page-link"><?php echo $page; ?></a></li>
                <?php endfor; ?>
            </ul class="pagination">
        </div>
    </div>
    </div>
    <div class="counter">
        <button disabled="true" class="port-counter">
            <b>&#931 Licenced XGS ports: <?php echo $total_ports ?></b>
        </button>
    </div>
    <div class="dropdown-user">
        <button class="dropbtn-user">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
            </svg>

            <b><?php echo $sessionUser ?></b>

        </button>
        <div class="dropdown-content-user">
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>