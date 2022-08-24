<?php
require('functions.php');

ini_set("display_errors","On");
error_reporting(E_ALL);
// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// Database settings. Change these for your database configuration.
/*$dbConfig = [
    'host' => 'localhost',
    'username' => 'user',
    'password' => 'secret',
    'name' => 'example_database'
];*/

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    //'email' => 'user@example.com',
    'return_url' => 'http://52.45.1.241/payment-success.php',
    'cancel_url' => 'http://52.45.1.241/payment-cancelled.php',
    'notify_url' => 'http://52.45.1.241/payments.php'
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

    // Grab the post data so that we can set up the query string for PayPal.
    // Ideally we'd use a whitelist here to check nothing is being injected into
    // our post data.
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = stripslashes($value);
    }

    // Set the PayPal account.
    //$data['business'] = $paypalConfig['email'];

    // Set the PayPal return addresses.
    $data['return'] = stripslashes($paypalConfig['return_url']);
    $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
    $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

    // Set the details about the product being purchased, including the amount and currency so that these aren't overridden by the form data.
    //$data['item_name'] = $itemName;
    //$data['amount'] = $itemAmount;
    //$data['currency_code'] = 'GBP';

    // Add any custom fields for the query string.
    //$data['custom'] = USERID;

    // Build the query string from the data.
    $queryString = http_build_query($data);

    // Redirect to paypal IPN
    header('location:' . $paypalUrl . '?' . $queryString);
    exit();

} else {
    // Handle the PayPal response.

    // Create a connection to the database
    $db = new PDO('sqlite:/var/www/cart.db');
    $db->query('PRAGMA foreign_keys = ON;');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $results = $db->query('SELECT * FROM payments WHERE txnid = (?)');
    $results->bindParam(1, $_POST['txn_id']);
    $results->execute();
    $re = $results->fetch();

    if($re) {
        $flag = false;
    }
    else $flag = true;

    if (verifyTransaction($_POST) && $flag) {
        file_put_contents("text.txt",    $item_number);
        $fp = fopen("text.txt", "w");
        foreach($_POST as $key => $value)
            fwrite($fp, "$key => $value \r\n");

        $currency = $_POST['mc_currency'];
        $email = $_POST['receiver_email'];
        // $salt; //from db
        $total_prod = array();
        $total = $_POST['mc_gross'];
        $custom = $_POST['custom'];

        for ($i = 0; $i < ($_POST['num_cart_items']); $i++) {
            $total_prod[$i]["pid"] = $_POST['item_number' . ($i+1)];
            $total_prod[$i]["quantity"] = $_POST['quantity' . ($i+1)];
            $total_prod[$i]["price"] = $_POST['mc_gross_' . ($i+1)];

            //remove stocks from prod
            $q = $db->prepare("SELECT stocks FROM products WHERE pid = (?);");
            $q->bindParam(1, $total_prod[$i]["pid"]);
            $q->execute();
            $r = $q->fetch();
            
            $new_quan = $r["STOCKS"]- (int) $total_prod[$i]["quantity"];
            if($new_quan < 0) {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Out of stocks. <br/><a href="javascript:history.back();">Back to main page.</a>';
                exit();
                break;
            } else {
                htmlspecialchars($total_prod[$i]["pid"]);
                $query = $db->prepare("UPDATE products SET stocks = (?) WHERE pid = (?)");
                $query->bindParam(1, $new_quan);
                $query->bindParam(2, $total_prod[$i]["pid"]);
                $query->execute();
            }
        }

        $key =0;
        $tmp1 =0;
        $tmp2 =0;
        $j =0;
        for ($i = 1; $i < ($_POST['num_cart_items']); $i++){
            $key =  $total_prod[$i]["pid"];
            $tmp1 =  $total_prod[$i]["quantity"];
            $tmp2 = $total_prod[$i]["price"];
            $j = $i -1;
            while($j >= 0 && $total_prod[$j]["pid"] > $key){
                $total_prod[$j + 1]["pid"] = $total_prod[$j]["pid"];
                $total_prod[$j + 1]["quantity"] =  $total_prod[$j]["quantity"];
                $total_prod[$j + 1]["price"] = $total_prod[$j]["price"];
                $j = $j -1;
            }
            $total_prod[$j + 1]["pid"] = $key;
            $total_prod[$j + 1]["quantity"] = $tmp1;
            $total_prod[$j + 1]["price"] = $tmp2;
        }

        $item_array['currency'] = $currency;
        $item_array['email'] = $email;
        // $item_array['cart'] = json_encode($total_prod);
        $item_array['cart'] = serialize($total_prod);
        $item_array['price'] = $total;

        $sql = $db->prepare("SELECT * FROM orders WHERE order_id = (?);");
        $sql->bindParam(1, $custom);
        $sql->execute();
        $r = $sql->fetch();

        $salt = $r['SALT'];
        // $digest = hash_hmac('sha256', json_encode($item_array), $salt);
        $digest = hash_hmac('sha256', serialize($item_array), $salt);
            $fp = fopen("realsam.txt", "a+");
            fwrite($fp, "payments.php\r\n");
            // fwrite($fp, json_encode($item_array));
            fwrite($fp, serialize($item_array));
            fwrite($fp, "\r\n");
            fwrite($fp, $digest);
        fclose($fp);

        if($digest == $r['DIGEST']) {
            //save in payments
            $query = $db->prepare("INSERT INTO payments (txnid, payment_amount, payment_status, item_id, created_time) VALUES (?, ?, ?, ?, ?)");
            $query->bindParam(1, $_POST['txn_id']);
            $query->bindParam(2, $_POST['mc_gross']);
            $query->bindParam(3, $_POST['payment_status']);
            $query->bindParam(4, $item_array['cart']);
            $query->bindParam(5, date('Y-m-d H:i:s'));
            $query->execute();
        }

    }else{
        //Payment failed
        header('Location: payment-cancelled.html');
        exit();
    }
}
?>