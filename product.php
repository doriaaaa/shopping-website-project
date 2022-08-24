<?php
require __DIR__.'/admin/lib/db.inc.php';
$category = ierg4210_cat_fetchall();
$pid = $_GET['pid'];
$product = ierg4210_prod_fetchOne($pid);
$catid = $_GET['catid'];
$cat_prod = ierg4210_cat_fetchOne($catid);
$oneCat = ierg4210_cat_fetchOne_cat($catid);

$all = ierg4210_prod_fetchall();

$cat = '<ul>';

foreach ($category as $value){
    $cat .= '<li><a href = "'.$value["CATID"].'"> '.$value["NAME"].'</a></li>';
}

$cat .= '</ul>';


// echo '<div id = "maincontent">
// <div id = "products">'.$cat.'
// </div>
// </div>';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <?php 
            foreach($product as $prod_elm) {
                $prod_name = $prod_elm['NAME'];
                echo '<title>'.$prod_name.'</title>';
            }
        ?>
        <!-- Infinite Ajax Scroll -->
        <script src="https://cdn.jsdelivr.net/npm/@webcreate/infinite-ajax-scroll/dist/infinite-ajax-scroll.min.js"></script>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
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
                            $category = $category_elm['NAME'];
                            $catid = $category_elm['CATID'];
                            htmlspecialchars($category);
                            htmlspecialchars($catid);
                            echo '<a class="dropdown-item" href="main.php?catid='.$catid.'">'.$category.'</a>';
                        }
                    ?>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb bg-light">
                                      <li class="breadcrumb-item active" aria-current="page"><a href="main.php">Home</a></li>
                                      <?php 
                                        foreach($oneCat as $cat_elm) {
                                            $catid = $cat_elm['CATID'];
                                            $prod_name = $cat_elm['NAME'];
                                            htmlspecialchars($catid);
                                            htmlspecialchars($prod_name);
                                            echo '<li class="breadcrumb-item active" aria-current="page"><a href="main.php?catid='.$catid.'">'.$prod_name.'</a></li>';
                                        }
                                      ?>
                                      <?php 
                                        foreach($product as $prod_elm) {
                                            $prod_name = $prod_elm['NAME'];
                                            htmlspecialchars($prod_name);
                                            echo '<li class="breadcrumb-item active" aria-current="page">'.$prod_name.'</li>';
                                        }
                                      ?>
                                    </ol>
                                </nav>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0 ms-lg-4">
                        <form class="d-flex">
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
                                        <div class="align-items-end text-right mr-1">
                                            <a class="btn btn-primary" id="check_out_cart">
                                                <span class="glyphicon glyphicon-shopping-cart"></span> Check out
                                            </a>
                                            <a class="btn btn-secondary" id="clear_cart">
                                                <i class="bi bi-trash"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Product section-->
        <section class="py-4">
            <div class="container px-4 px-lg-5 my-5">
                <?php
                    foreach($product as $prod_elm) {
                        $pid = $prod_elm['PID'];
                        $catid = $prod_elm['CATID'];
                        $prod_name = $prod_elm['NAME'];
                        $prod_price = $prod_elm['PRICE'];
                        $prod_desc = $prod_elm['DESCRIPTION'];
                        $prod_stocks = $prod_elm['STOCKS'];
                        htmlspecialchars($pid);
                        htmlspecialchars($catid);
                        htmlspecialchars($prod_name);
                        htmlspecialchars($prod_price);
                        htmlspecialchars($prod_desc);
                        htmlspecialchars($prod_stocks);
                        echo '
                        <div class="row gx-4 gx-lg-5 align-items-center">
                            <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="src/'.$pid.'.jpg" alt="..." /></div>
                            <div class="col-md-6">
                                <div class="small mb-1">SKU: BST-498</div>
                                <h1 class="display-5 fw-bolder" id="name'.$pid.'" value="'.$prod_name.'">'.$prod_name.'</h1>
                                <div class="fs-5 mb-5">
                                    <span>
                                        <div class="list-inline-item">$
                                            <div class="list-inline-item" id="price'.$pid.'" value="'.$prod_price.'">'.$prod_price.'</div>
                                        </div>
                                    </span>
                                </div>
                                <div class="fs-5 mb-5">
                                    <span>'.$prod_stocks.' stocks left!!</span>
                                </div>
                                <p class="lead">'.$prod_desc.'</p>
                                <div class="d-flex">
                                    <button name="add_to_cart" id="'.$pid.'" class="btn btn-outline-dark mt-auto text-center add_to_cart">
                                    <i class="bi-cart-fill me-1"></i>
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        </div>';
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
        <script src="js/scripts.js" async></script>
    </body>
</html>
