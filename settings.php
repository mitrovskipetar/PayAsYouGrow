 <?php
    session_start();
    $user = $_SESSION['username'];
?>
<head>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" href="style.css">
     <title>
         Settings
     </title>
 </head>
<body>
 <div class="justify-content-center col-4" style="margin-left: 35%; margin-top:5%;">
     <form id="settingsForm" action="settings.php" class="form form-custom" method="POST">
         <div class="card px-1 py-4">
             <div class="card-body">
                 <h5 class="card-title mb-3">Settings</h5>
                 <?php
                    if ($user == "admin") {
                    ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input style="width:100%" type="button" class="btn btn-block btn-outline-primary" onclick="location.href = 'register.php';" value="Add User">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input style="width:100%" type="button" class="btn btn-block btn-outline-primary" onclick="location.href = 'admin_users_list.php';" value="List Users">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input style="width:100%" type="button" class="btn btn-block btn-outline-primary" onclick="location.href = 'unregister.php';" value="Delete User">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input style="width:100%" type="button" class="btn btn-block btn-outline-primary" onclick="location.href = 'user_password_change.php';" value="Change Other User Password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                 <div class="row">
                     <div class="col-sm-12">
                         <div class="form-group">
                             <div class="input-group">
                                 <input type="button" class="btn btn-block btn-outline-primary" onclick="location.href = 'password_change.php';" value="Change Password">
                             </div>
                         </div>
                     </div>
                 </div>
                 <input type="button" class="btn btn-secondary ml-2" style="float:right; margin-right:5px;" onclick="location.href = 'index.php';" value="Back">
             </div>
         </div>
</body>

 