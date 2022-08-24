<?php
require __DIR__.'/admin/lib/db.inc.php';
// require __DIR__.'/auth-process.php';
include_once('auth-process.php');
include_once('auth.php');
include_once('verify.php');

$category = ierg4210_cat_fetchall();

$catid = $_GET['catid'];
if($catid == null) {
    $product = ierg4210_prod_fetchall();
} else 
{
    $product = ierg4210_cat_fetchOne($catid);
}
$cat = '<ul>';
foreach ($category as $value){
    $cat .= '<li><a href = "'.$value["CATID"].'"> '.$value["NAME"].'</a></li>';
}
$cat .= '</ul>';

session_start();
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
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <a class="navbar-brand">IERG4210 Shop</a>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                        foreach($category as $category_elm) {
                            $category_name = $category_elm['NAME'];
                            $catid = $category_elm['CATID'];
                            htmlspecialchars($catid);
                            htmlspecialchars($category_name);
                            echo '<a class="dropdown-item" href="main.php?catid='.$catid.'">'.$category_name.'</a>';
                        }
                    ?>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link" href="main.php">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-light">
                                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                                </ol>
                            </nav>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0 ms-lg-4">
                        <form class="form-inline my-2 my-lg-0 pr-3">
                            <text class="my-2 my-sm-0">Welcome, <?php echo htmlspecialchars($user); ?> </text>
                        </form>
                        <?php 
                        if (auth_admin() == 1) {
                            echo '<form class="form-inline my-2 my-lg-0 pr-3">
                                <a class="btn btn-outline-success my-2 my-sm-0" href="admin.php"><i class="bi bi-terminal"></i> Admin Panel</a>
                            </form>
                            <form class="form-inline my-2 my-lg-0 pr-3">
                                <a class="btn btn-outline-secondary my-2 my-sm-0" href="admin_order_records.php"><i class="bi bi-cart-check"></i> Orders</a>
                            </form>';
                        }
                        else if ($user != 'guest' && auth_admin() != 1) {
                            echo '<form class="form-inline my-2 my-lg-0 pr-3">
                                <a class="btn btn-outline-secondary my-2 my-sm-0" href="history.php"><i class="bi bi-cart-check"></i> History</a>
                            </form>';
                        }
                        ?>
                        <form class="form-inline my-2 my-lg-0 pr-3" id="logout" method="POST" action="admin-process.php?action=logout" enctype="multipart/form-data">
                            <button class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Submit"><?php if ($_SESSION["auth"] == false) echo '<i class="bi bi-key-fill"></i> Login'; else echo '<i class="bi bi-door-open"></i> Logout'?></button>
                            <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('logout'); ?>"/>
                        </form>
                        <div class="dropdown">
                            <button class="dropdown-toggle btn btn-outline-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Shopping Cart" id="cart-popover">
                                <i class="bi-cart-fill me-1"></i>
                                Cart
                                <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                                <span class="text-black ms-1 total_price">$ 0.00</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="cartlist">
                                <div id="popover_content_wrapper">
                                    <div id="cart_details">

                                    </div>
                                    <form action="payments.php" method="post" id="form1">
                                        <input type="hidden" name="cmd" value="_cart" />
                                        <input type="hidden" name="upload" value="1" />
                                        <input type="hidden" name="business" value="sb-6luxn15539390@business.example.com" />
                                        <input type="hidden" name="currency_code" value="HKD" />
                                        <input type="hidden" name="charset" value="utf-8" />
                                    </form>
                                    <div class="align-items-end text-right mr-1">
                                        <button id="checkout" type="submit" class="btn btn-primary checkout" form="form1">Checkout</button>
                                        <a class="btn btn-secondary" id="clear_cart">
                                            <i class="bi bi-trash"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-purple py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Lunar New Year Sale</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Buy 1 get 2 free!</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-4">
            <div class="card-container container px-4 px-lg-5 mt-5" id="card-container">
                <div class="item row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                        <?php
                            foreach($product as $prod_elm) {
                                $pid = $prod_elm['PID'];
                                $catid = $prod_elm['CATID'];
                                $prod_name = $prod_elm['NAME'];
                                $prod_price = $prod_elm['PRICE'];
                                $prod_stocks = $prod_elm['STOCKS'];
                                htmlspecialchars($pid);
                                htmlspecialchars($catid);
                                htmlspecialchars($prod_name);
                                htmlspecialchars($prod_price);
                                htmlspecialchars($prod_price);
                                echo '<div class="col mb-5">
                                    <div class="card h-100">
                                        <!-- Product image-->
                                        <a href="product.php?catid='.$catid.'&pid='.$pid.'">
                                            <img class="card-img-top" src="src/'.$pid.'.jpg" width="232" height="155" alt="stuff" />
                                        </a>
                                        <!-- Product details-->
                                        <div class="card-body p-4">
                                            <div class="text-center">
                                                <!-- Product name-->
                                                <h5 class="fw-bolder" id="name'.$pid.'" value="'.$prod_name.'">'.$prod_name.'</h5>
                                                <!-- Product price-->
                                                <div class="list-inline-item">$
                                                    <div class="list-inline-item" id="price'.$pid.'" value="'.$prod_price.'">'.$prod_price.'</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Product actions-->
                                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                            <div class="text-center">
                                                <button name="add_to_cart" id="'.$pid.'" class="btn btn-outline-dark mt-auto text-center add_to_cart">
                                                    Add to cart
                                                </button>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>';
                            }
                        ?>
                </div>
            </div>
            <div class="pagination">
                <?php
                    if($_GET['catid'] == null) {
                        echo '<a href="main.php" class="next">Next</a>';
                    } else {
                        htmlspecialchars($catid);
                        echo '<a href="main.php?catid='.$catid.'" class="next">Next</a>';
                    }
                ?>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-purple">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; IERG4210 Shop 2022</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>