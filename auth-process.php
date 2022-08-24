<?php
include_once('auth.php');
include_once('verify.php');

function fetch_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');

	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db;
}

function ierg4210_login() {
    if(empty($_POST['email']) || empty($_POST['pw']) 
    || !preg_match("/^[\w=+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $_POST['email'])
    || !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST["pw"])) {
        throw new Exception('Wrong Credentials: empty email and pw');
    }

    global $db;
    $db = fetch_DB();
    $pwd = $_POST['pw'];
    $email = $_POST['email'];
    htmlspecialchars($pwd);
    htmlspecialchars($email);
    $q=$db->prepare('SELECT * FROM account WHERE email = (?);'); 
    $q->bindParam(1, $email);
    $q->execute();
    $r=$q->fetch();
    $password = $r["HASHED_PASSWORD"];
    $salt = $r["SALT"];
    $admin_flag = $r["ADMIN_FLAG"];
    $hashed_password = hash_hmac('sha256', $pwd, $salt);

    if($password == $hashed_password){
        //Check if the hash of the password equals the one saved in database 
        //If yes, create authentication information in cookies and session 
        //program code on next slide
        $exp = time() + 3600 * 24 * 3;
        $token = array(
            'em'=>$r['EMAIL'],
            'exp'=>$exp,
            'k'=>hash_hmac('sha256', $exp.$r['HASHED_PASSWORD'], $r['SALT']),
            'admin_flag'=>$r['ADMIN_FLAG']
        );
        setcookie('auth', json_encode($token), $exp, '','', true, true);
        $_SESSION['auth'] = $token;
        session_regenerate_id();
        if($admin_flag == 1){
            $_SESSION['login'] = true;
            header('Location: admin.php', true, 302);
            exit();
        } else {
            $_SESSION['login'] = true;
            header('Location: main.php', true, 302);
            exit();
        }
    } else {
        header('Content-Type: text/html; charset=utf-8');
        echo '
        <head>
            <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
            <title>Login Page</title>
            <!-- Bootstrap icons-->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
            <!-- Core theme CSS (includes Bootstrap)-->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
            <link href="../css/styles.css" rel="stylesheet" />
        </head>
        <body style="background-color:bisque;">
            <div class="page-wrap d-flex flex-row align-items-center">
                <div class="container h-100 pt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <div class="mb-4 lead">Wrong email or password entered!</div>
                            <a href="../login.php" class="btn btn-secondary">Back to Login page</a>
                            <a href="../main.php" class="btn btn-primary">Visit the shop via Guest</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        ';
        exit();
    }
}

function ierg4210_logout() {
    //clear cookie and session
    setcookie('auth', '', time() - 3600 * 24 * 3, '/');
    session_destroy();
    //redirect to login page after logout
    header('Location: login.php', true, 302);
    exit();
}

function ierg4210_change_password() {
    global $db;
    $db = fetch_DB();
    $old_pwd = $_POST['pw'];
    $email = $_POST['email'];
    htmlspecialchars($old_pwd);
    htmlspecialchars($email);
    // htmlspecialchars($email);
    $q=$db->prepare('SELECT * FROM account WHERE email = (?);'); 
    $q->bindParam(1, $email);
    $q->execute();
    $r=$q->fetch();
    $old_db_password = $r["HASHED_PASSWORD"];
    $old_salt = $r["SALT"];
    $admin_flag = $r["ADMIN_FLAG"];
    $db_email = $r["EMAIL"];
    $old_hashed_password = hash_hmac('sha256', $old_pwd, $old_salt);

    if ($email != $db_email || $old_hashed_password != $old_db_password) {
        //prompt alert
        header('Content-Type: text/html; charset=utf-8');
        echo '
        <head>
            <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
            <title>Login Page</title>
            <!-- Bootstrap icons-->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
            <!-- Core theme CSS (includes Bootstrap)-->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
            <link href="../css/styles.css" rel="stylesheet" />
        </head>
        <body style="background-color:bisque;">
            <div class="page-wrap d-flex flex-row align-items-center">
                <div class="container h-100 pt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <div class="mb-4 lead">email or password not found!</div>
                            <a href="change_password.php" class="btn btn-secondary">Back to Reset page</a>
                            <a href="main.php" class="btn btn-primary">Visit the shop via Guest</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        ';
        exit();
    }
    else {
        $new_password = $_POST['updated_password'];
        htmlspecialchars($new_password);
        $new_salt = mt_rand();
        $new_hashed_password = hash_hmac('sha256', $new_password, $new_salt);
        $q=$db->prepare('UPDATE account SET hashed_password = (?), salt = (?) WHERE (email) = (?);'); 
        $q->bindParam(1, $new_hashed_password);
        $q->bindParam(2, $new_salt);
        $q->bindParam(3, $email);
        if ($q->execute()) {
            if (isset($_COOKIE['auth'])) {
                unset($_COOKIE['auth']);
                setcookie('auth', '', time() - 3600 * 24 * 3, '/'); // empty value and old timestamp
            }
            header('Location: login.php', true, 302);
            exit();
        }
    }
}
?>