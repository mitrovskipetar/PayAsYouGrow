<?php
include 'common.php';
//Check if user is logged in, if not redirect to login page
session_start();
if (!isset($_SESSION["loggedin"])) {
  header("location: login.php");
  exit;
}

$sessionUser = $_SESSION['username'];
$dbh = openCon();

$limit = 10;

$sql  = "SELECT count(DISTINCT(username)) FROM event_log";
$res = $dbh->prepare($sql);


$res->execute();
$total_results = $res->fetchColumn();
$total_pages = ceil($total_results / $limit);
if (!isset($_GET['page'])) {
  $page = 1;
} else {
  $page = $_GET['page'];
}
$starting_limit = ($page - 1) * $limit;
// $show  = "SELECT * FROM event_log ORDER BY date DESC LIMIT :start, :rows";
// $r->bindParam(':start', $starting_limit, PDO::PARAM_INT);
// $r->bindParam(':rows', $limit, PDO::PARAM_INT);

// Get all users from DB
$queue  = "SELECT username FROM users";
$r = $dbh->prepare($queue);
$r->execute();
$allUsers = $r->fetchAll(PDO::FETCH_OBJ);
//Get all licences from DB
$sql  = "SELECT * FROM licence_data";
$res = $dbh->prepare($sql);
$res->execute();
$allLicences = $res->fetchAll(PDO::FETCH_OBJ);

//Get the event logs
$query  = "SELECT * FROM event_log";
$result = $dbh->prepare($query);
$result->execute();
$allEvents = $result->fetchAll(PDO::FETCH_OBJ);

//SUM the xgs ports for each user
$userMap;
$portMap;
$dateMap;

$i = 0;
foreach ($allUsers as $user) {

  $totalPorts = 0;
  $name = $user->username;
  foreach ($allLicences as $licence) {
    if ($licence->user == $name) {
      $totalPorts += $licence->xgs_ports;
    }
  }
  $tempDateMap = null;
  //calculate the date last edited
  foreach ($allEvents as $event) {

    if ($event->username == $name) {
      if (!isset($tempDateMap) || (strtotime($event->date) > strtotime($tempDateMap))) {
        $tempDateMap = $event->date;
      }
    }
  }
  if (isset($tempDateMap)) {
    $userMap[$i] = $name;
    $portMap[$i] = $totalPorts;
    $dateMap[$i] = $tempDateMap;
  }



  $i++;
}
$userDateMap = array_combine($userMap, $dateMap);
$userPortMap = array_combine($userMap, $portMap);

?>


<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <!-- <script>
 		$(document).ready(function() {
 			$("#passwordForm").submit(function() {
 				alert("The password has been changed!");
 			});
 		});
 	</script> -->
  <title>
    Usage Overview
  </title>
</head>

<body>
  <a href="usage_overview.php">
    <img id="logo_image" src="iskratel-logo.png" />
  </a>
  <div class="container-table">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col" class="col-md-2">User</th>
          <th scope="col" class="col-md-2">&#931 Licenced XGS ports</th>
          <th scope="col" class="col-md-2">Last Edited</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($allUsers as $user) {
          if ($userDateMap[$user->username] != null) { ?>
            <tr>
              <td class="col-md-2"><?= $user->username ?></td>
              <td class="col-md-2"><?= $userPortMap[$user->username] ?></td>
              <td class="col-md-2"><?= $userDateMap[$user->username] ?></td>
              <td class="col-md-2"><input type="button" class="btn btn-outline-primary" onclick="location.href='user_report.php?username=<?= $user->username; ?>';" value="Full Event Log"></td>
            </tr>
        <?php }
        } ?>
      </tbody>
    </table>
    <div style="align-items:center;text-align: center;position:absolute; bottom:5%; left:47%;">
      <ul class="pagination" style="display: inline-flex">
        <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
          <li class="page-item"><a href='<?php echo "?page=$page"; ?>' class="page-link"><?php echo $page; ?></a></li>
        <?php endfor; ?>
      </ul class="pagination">
    </div>
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