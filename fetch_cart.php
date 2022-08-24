<?php

session_start();

$total_price = 0;
$total_item = 0;

$output = '
<div class="table-responsive" id="order_table">
	<table class="table table-bordered table-striped">
		<tr>  
            <th width="30%">Product Name</th>  
            <th width="30%">Quantity</th>  
            <th width="20%">Price</th>  
            <th width="30%">Total</th>  
        </tr>
';

$form = '';
if(!empty($_SESSION["shopping_cart"]))
{
	foreach($_SESSION["shopping_cart"] as $keys => $values)
	{
		$output .= '
		<tr class="tableRow'.$values['pid'].'">
			<td id="cart-name'.$values['pid'].'">'.$values['name'].'</td>
			<td>
				<div class="input-group-sm d-inline-flex align-items-center">
					<div class="input-group-btn">
						<button type="button" class="btn btn-danger btn-number-minus btn-sm" id="minus'.$values['pid'].'" data-type="minus">
							<i class="bi bi-arrow-down-short"></i>
						</button>
					</div>
					<div type="text" class="form-control input-number input-group-text justify-content-center w-25" id="quantity'.$values['pid'].'" value="'.$values['quantity'].'" min="1" max="100">'.$values['quantity'].'</div>
					<div class="input-group-btn">
						<button type="button" class="btn btn-success btn-number-plus btn-sm" id="plus'.$values['pid'].'" data-type="plus">
							<i class="bi bi-arrow-up-short"></i>
						</button>
					</div>
				</div>
			</td>
			<td align="right" id="cart-price'.$values['pid'].'">$'.$values['price'].'</td>
			<td align="right">$'.number_format($values['quantity'] * $values['price'], 2).'</td>
		</tr>
		';
		$total_price = $total_price + ($values['quantity'] * $values['price']);
		$total_item = $total_item + 1;
		$form .= '<input type="hidden" name="item_name_'.$total_item.'" value="'.$values['name'].'" />
		<input type="hidden" name="amount_'.$total_item.'" value="'.$values['price'].'" />
		<input type="hidden" name="quantity_'.$total_item.'" value="'.$values['quantity'].'" />
		<input type="hidden" name="item_number_'.$total_item.'" value="'.$values['pid'].'" />';
	}
	$output .= '
	<tr>  
        <td colspan="3" align="right">Total</td>  
        <td align="right">$'.number_format($total_price, 2).'</td>  
    </tr>
	';
}
else
{
	$output .= '
    <tr>
    	<td colspan="5" align="center">
    		Your Cart is Empty!
    	</td>
    </tr>
    ';
}
$output .= '</table></div>';
$data = array(
	'form'				=> 	$form,
	'cart_details'		=>	$output,
	'total_price'		=>	'$' . number_format($total_price, 2),
	'total_item'		=>	$total_item
);	

echo json_encode($data);

?>