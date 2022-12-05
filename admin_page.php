<?php
include 'common.php';

//Check if user is logged in, if not redirect to login page
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: login.php");
    exit;
}

$user = $_SESSION['username'];
$dbh = openCon();

//Pagination Setup
$limit = 10;

if ($user == "admin") {
    $sql  = "SELECT count(*) FROM licence_data";
    $res = $dbh->prepare($sql);
} else {
    $sql  = "SELECT count(*) FROM licence_data WHERE user=:user";
    $res = $dbh->prepare($sql);
    $res->bindParam(':user', $user, PDO::PARAM_STR);
}

$res->execute();
$total_results = $res->fetchColumn();
$total_pages = ceil($total_results / $limit);
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$starting_limit = ($page - 1) * $limit;

// Fetching licence data for the table
if ($user == "admin") {
    $show  = "SELECT * FROM licence_data ORDER BY licence_id DESC LIMIT :start, :rows";
    $r = $dbh->prepare($show);
} else {
    $show  = "SELECT * FROM licence_data WHERE user=:user ORDER BY licence_id DESC LIMIT :start, :rows";
    $r = $dbh->prepare($show);
    $r->bindParam(':user', $user, PDO::PARAM_STR);
}


$r->bindParam(':start', $starting_limit, PDO::PARAM_INT);
$r->bindParam(':rows', $limit, PDO::PARAM_INT);
$r->execute();
$objects = $r->fetchAll(PDO::FETCH_OBJ);
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $('#formModal').on('shown.bs.modal', function() {
            $('#modalButton').trigger('focus')
        })
    </script>
    <!-- <script>
 		$(document).ready(function() {
 			$("#passwordForm").submit(function() {
 				alert("The password has been changed!");
 			});
 		});
 	</script> -->
    <title>
        Admin Page
    </title>
</head>

<body>
    <a href="index.php">
        <img id="logo_image" src="iskratel-logo.png" />
    </a>
    <div class="container-admin-table">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="col-md-2">Licence ID</th>
                    <?php
                    if ($user == "admin") {
                    ?>
                        <th scope="col" class="col-md-2">User</th>
                    <?php
                    }
                    ?>
                    <th scope="col" class="col-md-2">Product</th>
                    <th scope="col" class="col-md-2">Serial Number</th>
                    <th scope="col" class="col-md-2">GPON Ports</th>
                    <th scope="col" class="col-md-2">XGSPON Ports</th>
                    <th scope="col" class="col-md-2"></th>
                    <?php
                    if ($user == "admin") {
                    ?>
                        <th scope="col" class="col-md-2"></th>
                    <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $data) : ?>
                    <tr>
                        <th scope="row" class="col-md-2"><?= $data->licence_id; ?></th>
                        <?php
                        if ($user == "admin") {
                        ?>
                            <td class="col-md-2"><?= $data->user ?></td>
                        <?php
                        }
                        ?>
                        <td class="col-md-2"><?= $data->product ?></td>
                        <td class="col-md-2"><?= $data->serial_number ?></td>
                        <td class="col-md-2"><?= $data->gpon_ports ?></td>
                        <td class="col-md-2"><?= $data->xgs_ports ?></td>
                        <td class="col-md-2"><input type="button" class="btn btn-outline-primary" onclick="location.href='licence_generate.php?licence_id=<?= $data->licence_id; ?>&product=<?= $data->product; ?>&serial_number=<?= $data->serial_number; ?>&gpon_ports=<?= $data->gpon_ports; ?>&xgs_ports=<?= $data->xgs_ports; ?>&valid_until=<?= $data->valid_until; ?>';" value="Download"></td>
                        <?php
                        if ($user == "admin") {
                        ?>
                            <td class="col-md-2"><input type="button" class="btn btn-outline-danger" onclick="location.href='licence_remove.php?licence_id=<?= $data->licence_id ?>&serial_number=<?= $data->serial_number ?>';" value="Delete"></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="align-items: center;text-align: center;position:absolute; bottom:5%; left:47%;">
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

            <b><?php echo $user ?></b>

        </button>
        <div class="dropdown-content-user">
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
</body>

<?php
?>