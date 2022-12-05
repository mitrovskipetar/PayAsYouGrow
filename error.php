<?php
$message = $_GET['msg'];
$error = "404";
if (strpos($message, "Duplicate entry")) {
    $error = "Duplicate Serial Number";
}else{
    $error = $message;
}

?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>
        Error!
    </title>
</head>
<div class="justify-content-center col-4" style="margin-left: 35%; margin-top:5%;">
    <form id="errorForm" action="password_change.php" class="form form-custom" method="POST">
        <div class="card px-1 py-4">
            <div class="card-body">
                <h5 style="margin-left:10%;color: #FF0000;">Error: <?php echo $error ?></h5>
            </div class="button-center">
            <input type="button" class="btn btn-secondary ml-2" style="" onclick="location.href = 'index.php';" value="Back to main page">
        </div>
</div>
</div>
</form>
</div>