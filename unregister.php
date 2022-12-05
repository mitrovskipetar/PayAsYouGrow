<?php
include 'common.php';
$dbh = OpenCon();

function deleteUser($username)
{

    global $dbh;
    $sql = 'DELETE FROM users WHERE username=:username';
    $statement = $dbh->prepare($sql);
    $statement->execute([':username' => $username]);
}


if (isset($_POST['username'])) {
    deleteUser($_POST['username']);
    header("Refresh:0; url=index.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #009ee0
        }

        .card {
            margin: 0;
            position: absolute;
            top: 40%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            width: 400px;
            background-color: #fff;
            border: none;
            border-radius: 12px
        }

        label.radio {
            cursor: pointer;
            width: 100%
        }

        label.radio input {
            position: absolute;
            top: 0;
            left: 0;
            visibility: hidden;
            pointer-events: none
        }

        label.radio span {
            padding: 7px 14px;
            border: 2px solid #eee;
            display: inline-block;
            color: #039be5;
            border-radius: 10px;
            width: 100%;
            height: 48px;
            line-height: 27px
        }

        label.radio input:checked+span {
            border-color: #039BE5;
            background-color: #81D4FA;
            color: #fff;
            border-radius: 9px;
            height: 48px;
            line-height: 27px
        }

        .form-control {
            margin-top: 10px;
            height: 48px;
            border: 2px solid #eee;
            border-radius: 10px
        }

        .form-control:focus {
            box-shadow: none;
            border: 2px solid #039BE5
        }

        .agree-text {
            font-size: 12px
        }

        .terms {
            font-size: 12px;
            text-decoration: none;
            color: #039BE5
        }

        .confirm-button {
            height: 50px;
            border-radius: 10px
        }


        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="wrapper">
            <h2>Delete User</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control">
                    <input type="submit" class="btn btn-primary" style="margin-top: 10px; float:right" value="Submit">
                    <input type="button" class="btn btn-secondary ml-2" style="margin-top: 10px; float:right; margin-right:5px;" onclick="location.href = 'settings.php';" value="Back">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
            </form>
        </div>
    </div>
</body>

</html>