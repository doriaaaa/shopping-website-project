<?php
    require __DIR__.'/auth-process.php';
    include_once('verify.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <title>Change Password</title>
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body style="background-color:bisque;">
    <!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card border-0 shadow rounded-3 my-5">
                        <div class="card-body p-4 p-sm-5">
                            <h5 class="card-title text-center mb-5 fw-light fs-5">Change Password</h5>
                            <h6 class="text-center mb-5 fw-light fs-5">
                                <i class="bi bi-server"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-terminal"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-three-dots"></i>
                                <i class="bi bi-shop"></i>
                            </h6>
                            <form id="change_password" method="POST" action="admin-process.php?action=change_password" enctype="multipart/form-data">
                                <div class="form-floating mb-3">
                                    <label for="floatingInput">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" autofocus>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="floatingPassword">*Old Password</label>
                                    <input type="password" name="pw" class="form-control" id="pw" placeholder="Old Password" required>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="floatingPassword">*New Password</label>
                                    <input type="password" name="updated_password" class="form-control" id="updated_pw" placeholder="New Password" required>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-login text-uppercase fw-bold mb-4" type="submit">Change Password</button>
                                </div>
                                <div class="d-grid">
                                    <a class="btn btn-secondary btn-login text-uppercase fw-bold mb-4" href="login.php">Back to Login</a>
                                </div>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('change_password'); ?>"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>