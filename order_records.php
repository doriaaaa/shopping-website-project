<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function order_records() {
    $user = auth();
    htmlspecialchars($user);
    $output ='';

    $db = new PDO('sqlite:/var/www/cart.db');
    $db->query('PRAGMA foreign_keys = ON;');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $sql = $db->prepare("SELECT * FROM orders WHERE email = (?) ORDER BY order_id DESC");
    $sql->bindParam(1, $user);
    $sql->execute();

    $j=5;
    
    while($r = $sql->fetch()) {
        if($j > 0) {
            $j--;
        } 
        else 
        {
            break;
        }
        //for one entry
        // $data = json_decode($r['LIST']);
        $data = unserialize($r['LIST']);
        $length = count($data);
        $output .= 
        '<tr>
            <td>'.$r['ORDER_ID'].'</td>
            <td>
        ';
        //list contains name and quantity, but with multiple names and quantity
        //for one list
        for($i=0; $i < count($data); $i++) {
            //$list['name']
            //$list['quantity']
            $output .= '<div>'.$data[$i]['name'].'</div>';
        }
        $output .= '</td><td>';
        for($i=0; $i < count($data); $i++) {
            $output .= '<div>'.$data[$i]['quantity'].'</div>';
        }
        $time = $r["TIME"];
        $output .=
        '</td><td>'.$r["TIME"].'</td>
        </tr>';
    }

    return $output;
}

function admin_order_history() {
    $db = new PDO('sqlite:/var/www/cart.db');
    $db->query('PRAGMA foreign_keys = ON;');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $sql = $db->prepare("SELECT * FROM payments;");
    $sql->execute();

    $output ='';
    while($r = $sql->fetch()) {
        // $data = json_decode($r['ITEM_ID']);
        $data = unserialize($r['ITEM_ID']);
        $length = count($data);
        // header('Content-Type: text/html; charset=utf-8');
        // echo $length;
        // echo var_dump($r);
        // echo $length;
        // echo '<br/><a href="javascript:history.back();">Back to admin panel.</a>';
        // exit();

        $output .= 
        '<tr>
            <td>'.$r['ID'].'</td>
            <td>'.$r['TXNID'].'</td>
            <td>'.$r['PAYMENT_STATUS'].'</td>
            <td>
        ';
        for($i=0; $i < count($data); $i++) {
        //     //$list['name']
        //     //$list['quantity']
            $q = $db->prepare("SELECT * FROM products WHERE pid = (?);");
            $q->bindParam(1, $data[$i]['pid']);
            $q->execute();
            $result = $q->fetch();
            $output .= '<div>'.$result['NAME'].'</div>';
        }
        $output .= '</td><td>';
        for($i=0; $i < count($data); $i++) {
            $output .= '<div>'.$data[$i]['quantity'].'</div>';
        }
        $output .= '</td><td>'.$r['CREATED_TIME'].'</td></tr>';
    }
    return $output;
}
?>