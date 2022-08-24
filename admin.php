<?php
require __DIR__.'/admin/lib/db.inc.php';
include_once('auth.php');
include_once('verify.php');

session_start();
if(auth() == false || auth_admin() != 1) {
    header('Location: login.php');
    exit();
} 

$res = ierg4210_cat_fetchall();
$prod = ierg4210_prod_fetchall();
$prodoptions = '';
$options = '';

foreach ($res as $value){
    htmlspecialchars($value["CATID"]);
    htmlspecialchars($value["NAME"]); 
    $options .= '<option value="'.$value["CATID"].'"> '.$value["NAME"].' </option>';
}
foreach ($prod as $value){
    htmlspecialchars($value["PID"]);
    htmlspecialchars($value["NAME"]); 
    $prodoptions .= '<option value="'.$value["PID"].'"> '.$value["NAME"].' </option>';
}
?>
<html>
    <head>
    <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/styles.css">
        <title>IERG4210 Shop Admin Panel</title>
        <style>
        .upload-container input {
            border: 1px solid #ced4da;
            background: white;
            outline: 1.5px dashed #ced4da;
            outline-offset: -7px;
            padding: 20px 20px 20px 20px;
            text-align: center !important;
            width: 100%;
        }

        .upload-container input:hover {
            background: #bbd4ee8a;
        }
        </style>
    </head>
    <body style="background-color:bisque;"> 
        <nav class="row navbar-light px-5 pt-3">
            <label class="navbar-brand ml-3">IERG4210 Shop Admin Panel</label>
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
                <div class="card-group">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Add New Category</h5>
                            <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label for="cat_name"> Name *</label>
                                    <div> <input class="form-control" id="cat_name" type="text" name="name" required="required" pattern="^[A-Za-z0-9 ]+$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                                </div>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('cat_insert');?>"/>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Edit Category</h5>
                            <form id="cat_edit" method="POST" action="admin-process.php?action=cat_edit" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label for="prod_catid"> Category *</label>
                                    <div> <select class="custom-select custom-select-sm" id="prod_catid" name="catid"><?php echo $options; ?></select></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_name"> Name *</label>
                                    <div> <input class="form-control" id="cat_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
                                </div>
                                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('cat_edit'); ?>"/>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Delete Category</h5>
                            <form id="cat_delete" method="POST" action="admin-process.php?action=cat_delete" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label for="prod_catid"> Category *</label>
                                    <div> <select class="custom-select custom-select-sm" id="prod_catid" name="catid"><?php echo $options; ?></select></div>
                                </div>
                                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('cat_delete'); ?>"/>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-group">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Add New Product</h5>
                            <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label for="prod_catid"> Category *</label>
                                    <div> <select class="custom-select custom-select-sm" id="prod_catid" name="catid"><?php echo $options; ?></select></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_name"> Name *</label>
                                    <div> <input class="form-control" id="prod_name" type="text" name="name" required="required" pattern="^[A-Za-z0-9 ]+$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_price"> Price *</label>
                                    <div> <input class="form-control" id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_price"> Stocks *</label>
                                    <div> <input class="form-control" id="prod_stocks" type="text" name="stocks" required="required" pattern="^\d+\.?\d*$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_desc"> Description *</label>
                                    <div> <textarea class="form-control" id="prod_desc" type="text" name="description"> </textarea> </div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_image"> Image * </label>
                                    <div class="upload-container" id="upload-container">
                                        <input type="file" name="file" required="true" accept="image/jpeg" id="file_upload" onchange="loadFile(event)"/>
                                        <img class="my-1" id="output" width="200" />
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('prod_insert'); ?>"/>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Edit Product</h5>
                            <form id="prod_edit" method="POST" action="admin-process.php?action=prod_edit" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label for="prod_catid"> Product *</label>
                                    <div> <select class="custom-select custom-select-sm" id="prod_catid" name="pid"><?php echo $prodoptions; ?></select></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_name"> Name *</label>
                                    <div> <input class="form-control" id="prod_name" type="text" name="name" required="required" pattern="^[A-Za-z0-9 ]+$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_price"> Price *</label>
                                    <div> <input class="form-control" id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_stocks"> Stocks *</label>
                                    <div> <input class="form-control" id="prod_stocks" type="text" name="stocks" required="required" pattern="^\d+\.?\d*$"/></div>
                                </div>
                                <div class="form-group my-3">
                                    <label for="prod_desc"> Description *</label>
                                    <div> <textarea class="form-control" id="prod_desc" type="text" name="description"> </textarea></div>
                                </div>
                                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('prod_edit'); ?>"/>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Delete Product</h5>
                            <form id="prod_delete" method="POST" action="admin-process.php?action=prod_delete" enctype="multipart/form-data">
                                <div class="form-group my-3">
                                    <label for="prod_pid"> Product *</label>
                                    <div> <select class="custom-select custom-select-sm" id="prod_pid" name="pid"><?php echo $prodoptions; ?></select></div>
                                </div>
                                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('prod_delete'); ?>"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<script>
    var loadFile = function(event) {
        // delete first then create
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>