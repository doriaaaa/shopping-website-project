<?php 

include_once('/admin/lib/db.inc.php');
include_once('auth.php');
session_start();

ini_set("display_errors","On");
error_reporting(E_ALL);
// header('Content-type: application/json; charset=UTF-8');

$db = new PDO('sqlite:/var/www/cart.db');
$db->query('PRAGMA foreign_keys = ON;');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$salt = mt_rand().mt_rand();
$currency = "HKD";
$email = "sb-6luxn15539390@business.example.com"; //merchant address
$total = 0;
$total_prod = array();
$i = 0;

foreach($_POST["cart"] as $keys => $values){
    $pid = $keys;
    $quantity = $values;

    // //fetch quantity
    $sql = $db->prepare("SELECT * FROM products WHERE pid = (?)");
    $sql->bindParam(1, $pid);
    $sql->execute();
    $r = $sql->fetch();

    // //update quantity in db
    // $new_quan = $r["STOCKS"]-$quantity;
    // if($new_quan < 0) {
    //     header('Content-Type: text/html; charset=utf-8');
    //     echo 'Out of stocks. <br/><a href="javascript:history.back();">Back to main page.</a>';
    //     exit();
    // }
    
    // update db quantity
    // $q = $db->prepare("UPDATE products SET stocks = (?) WHERE pid = (?)");
    // $q->bindParam(1, $new_quan);
    // $q->bindParam(2, $keys);
    // $q->execute();

    // //calculate total price
    $total += $quantity * $r['PRICE']; //dec

    $total_prod[$i]["pid"] = $pid;
    $total_prod[$i]["quantity"] = $quantity;
    $total_prod[$i]["price"] = (string) ($r['PRICE']*$quantity);

    $list[$i]['name'] = $r['NAME'];
    $list[$i]['quantity'] = $quantity;
    
    $i++;
}

$item_array['currency'] = $currency;
$item_array['email'] = $email;
// $item_array['cart'] = json_encode($total_prod); 
$item_array['cart'] = serialize($total_prod); 
$item_array['price'] = (string) $total;

//generate digest
//digest include pid + quantity but excludes name + quantity
// $digest = hash_hmac('sha256', json_encode($item_array), $salt);
$digest = hash_hmac('sha256', serialize($item_array), $salt);
    $fp = fopen("old.txt", "a+");
    fwrite($fp, "gen_digest\r\n");
    // fwrite($fp, json_encode($item_array));
    fwrite($fp, serialize($item_array));
    fwrite($fp, "\r\n");
    fwrite($fp, $digest);
    fwrite($fp, "\r\n");
fclose($fp);

//user email
if(!empty($_SESSION["auth"])){
    $user = auth();
} else{
    $user = 'guest';
}
//insert digest to db
$query = $db->prepare("INSERT INTO orders (email, salt, digest, list, time) VALUES (?, ?, ?, ?, ?)");
$query->bindParam(1, $user);
$query->bindParam(2, $salt);
$query->bindParam(3, $digest);
// $query->bindParam(4, json_encode($list));
$query->bindParam(4, serialize($list));
$query->bindParam(5, date('Y-m-d'));
$query->execute();

//return custom = order, invoice = digest
//custom
$lastId = $db->lastInsertId();

$data = array(
    // 'cart'   => json_encode($item_array),
    'cart'   => serialize($item_array),
    'lastId' => $lastId,
    'digest' => $digest,
);

header('Content-Type: application/json');
ob_clean();
echo json_encode($data);
?>