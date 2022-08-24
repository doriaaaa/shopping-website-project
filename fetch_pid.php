<?php
    $db = new PDO('sqlite:/var/www/cart.db');
    $db->query('PRAGMA foreign_keys = ON;');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $key = $_REQUEST['pid'];
    $query = "SELECT * FROM products WHERE pid = ".$key.";";
    $q = $db->prepare($query);
    if ($q->execute())
        $val = $q->fetchAll();
	foreach($val as $temp){
        $item_array = array(
            'pid'               =>     $_POST['pid'],  
            'name'              =>     $_POST['name'],  
            'price'             =>     $_POST['price'],  
        );
        echo json_encode($item_array);
	}
?>