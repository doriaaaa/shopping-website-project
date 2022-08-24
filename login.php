<?php
    require __DIR__.'/auth-process.php';
    include_once('auth.php');
    include_once('verify.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <title>Login Page</title>
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
                            <h5 class="card-title text-center mb-5 fw-light fs-5">IERG4210 Shop Login Panel</h5>
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
                            <form id="login" method="POST" action="admin-process.php?action=<?php echo ($action = 'login')?>" enctype="multipart/form-data">
                                <div class="form-floating mb-3">
                                    <label for="floatingInput">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" autofocus>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="floatingPassword">Password</label>
                                    <input type="password" name="pw" class="form-control" id="pw" placeholder="Password" required>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                                    <label class="form-check-label" for="rememberPasswordCheck">
                                    Remember password
                                    </label>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-login text-uppercase fw-bold mb-4" type="submit">Sign in</button>
                                    <a class="btn btn-secondary btn-login text-uppercase fw-bold mb-4 float-right" href="main.php">Shop as guest</a>
                                </div>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
                            </form>
                            <a href="change_password.php" class="forgot-password-link">Forgot password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>