<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    </head>
    <body>
        <!-- Sample nav bar-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><span class="sr-only"></span>Demo Shopping Site</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Login</a>
                </li>
                </ul>
            </div>
        </nav>

        <!--Shopping Cart-->
        <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Total</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-sm-8 col-md-6">
                                    <div class="media">
                                        <a class="thumbnail pull-left" href="#"> <img class="media-object" src="http://icons.iconarchive.com/icons/custom-icon-design/flatastic-2/72/product-icon.png" style="width: 72px; height: 72px;"> </a>
                                        <div class="media-body">
                                            <h4 class="media-heading"><a href="#">Apple gift card</a></h4>
                                            <h5 class="media-heading"> by <a href="#">Apple</a></h5>
                                            <span>Status: </span><span class="text-success"><strong>In Stock</strong></span>
                                        </div>
                                    </div></td>
                                    <td class="col-sm-1 col-md-1" style="text-align: center">
                                    <input type="email" class="form-control" id="exampleInputEmail1" value="3">
                                    </td>
                                    <td class="col-sm-1 col-md-1 text-center"><strong>$10.0</strong></td>
                                    <td class="col-sm-1 col-md-1 text-center"><strong>$30.0</strong></td>
                                    <td class="col-sm-1 col-md-1">
                                    <button type="button" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span> Remove
                                    </button></td>
                                </tr>
                                <tr>
                                    <td class="col-md-6">
                                    <div class="media">
                                        <a class="thumbnail pull-left" href="#"> <img class="media-object" src="http://icons.iconarchive.com/icons/custom-icon-design/flatastic-2/72/product-icon.png" style="width: 72px; height: 72px;"> </a>
                                        <div class="media-body">
                                            <h4 class="media-heading"><a href="#">Google Play gift card</a></h4>
                                            <h5 class="media-heading"> by <a href="#">Google</a></h5>
                                            <span>Status: </span><span class="text-success"><strong>In Stock</strong></span>
                                        </div>
                                    </div></td>
                                    <td class="col-md-1" style="text-align: center">
                                    <input type="email" class="form-control" id="exampleInputEmail1" value="2">
                                    </td>
                                    <td class="col-md-1 text-center"><strong>$10.0</strong></td>
                                    <td class="col-md-1 text-center"><strong>$20.0</strong></td>
                                    <td class="col-md-1">
                                    <button type="button" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span> Remove
                                    </button></td>
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>   </td>
                                    <td><h5>Subtotal</h5></td>
                                    <td class="text-right"><h5><strong>$50.0</strong></h5></td>
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>   </td>
                                    <td><h3>Total</h3></td>
                                    <td class="text-right"><h3><strong>$50.0</strong></h3></td>
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>
                                    <td>



            
                                    <form action="payments.php" method="post" id="form1">
                                        <input type="hidden" name="cmd" value="_cart" />
                                        <input type="hidden" name="upload" value="1" />
                                        <input type="hidden" name="business" value="sb-5dmam5523323@business.example.com" />
                                        <input type="hidden" name="currency_code" value="HKD" />
                                        <input type="hidden" name="charset" value="utf-8" />
                                        <input type="hidden" name="item_name_1" value="Apple gift card" />
                                        <input type="hidden" name="amount_1" value="10.0" />
                                        <input type="hidden" name="quantity_1" value="3" />
                                        <input type="hidden" name="item_name_2" value="Google Play gift card" />
                                        <input type="hidden" name="amount_2" value="10.0" />
                                        <input type="hidden" name="quantity_2" value="2" />
                                        <!--must be unique-->
                                        <!--
                                        <input type="hidden" name="custom" value="0" />
                                        <input type="hidden" name="invoice" value="0" />
-->
                                        <input type="submit" class="btn btn-success" form="form1" value="Checkout">
                                    </form>


                                    <button type="button" class="btn btn-success" type="submit" form="form1" value="Submit" style = "display: none">
                                        Checkout <span class="glyphicon glyphicon-play"></span>
                                    </button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


 



    </body>
</html>