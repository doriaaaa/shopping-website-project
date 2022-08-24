<?php
require __DIR__.'/admin/lib/db.inc.php';
// require __DIR__.'/auth-process.php';
include_once('auth-process.php');
include_once('auth.php');
include_once('verify.php');
include_once('order_records.php');

if(!empty($_SESSION["auth"])){
    $user = auth();
}
else{
    $user = 'guest';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <script src="https://cdn.jsdelivr.net/npm/@webcreate/infinite-ajax-scroll/dist/infinite-ajax-scroll.min.js"></script>
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body style="background-color:bisque;">
        <nav class="row navbar-light px-5 pt-3">
            <label class="navbar-brand ml-3">User Order History</label>
            <a class="ml-auto" href="main.php">
                <button class="btn btn-outline-success mr-3"><i class="bi bi-shop"></i> Go to Shop</button>
            </a>
            <form id="logout" method="POST" action="admin-process.php?action=<?php echo ($action = 'logout');?>" enctype="multipart/form-data">
                <button class="btn btn-outline-info mr-3" type="submit" value="Submit"><i class="bi bi-door-open"></i> Logout</button>
                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
            </form>
        </nav>
        <section class="py-2">
            <div class="container-fluid px-5">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Order Records</h5>
                    <div class="table-responsive" id="order_table">
                        <table class="table table-bordered table-striped">
                            <tr>  
                                <th width="20%">Order ID</th>  
                                <th width="30%">Products</th>  
                                <th width="20%">Quantity</th>  
                                <th width="30%">Order Time</th>
                            </tr>
                            <?php echo order_records(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </section>        
    </body>
<html>
