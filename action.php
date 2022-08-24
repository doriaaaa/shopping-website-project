<?php

//action.php
include_once('/admin/lib/db.inc.php');
session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "add")
	{
		if(isset($_SESSION["shopping_cart"]))
		{
			$is_available = 0;
			foreach($_SESSION["shopping_cart"] as $keys => $values)
			{
				if($_SESSION["shopping_cart"][$keys]['pid'] == $_POST['pid'])
				{
					$is_available++;
					$_SESSION["shopping_cart"][$keys]['quantity'] = $_SESSION["shopping_cart"][$keys]['quantity'] + $_POST['quantity'];
					if($_SESSION["shopping_cart"][$keys]['quantity'] == 0) 
					{
						unset($_SESSION["shopping_cart"][$keys]);
					}
				}
			}
			if($is_available == 0)
			{
				$item_array = array(
					'pid'            =>     $_POST['pid'],  
					'name'           =>     $_POST['name'],  
					'price'          =>     $_POST['price'],  
					'quantity'       =>     $_POST['quantity']
				);
				$_SESSION["shopping_cart"][] = $item_array;
			}
		}
		else
		{
			$item_array = array(
				'pid'               =>     $_POST['pid'],  
				'name'              =>     $_POST['name'],  
				'price'             =>     $_POST['price'],  
				'quantity'	        =>     $_POST['quantity']
			);
			$_SESSION["shopping_cart"][] = $item_array;
		}
	}
	
	if($_POST["action"] == 'empty')
	{
		unset($_SESSION["shopping_cart"]);
	}
}

?>