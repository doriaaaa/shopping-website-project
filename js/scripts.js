/*!
* Start Bootstrap - Shop Homepage v5.0.4 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project
// import if you use the NPM package (not needed if you use CDN)
if (document.getElementById('card-container')) {
	let ias = new InfiniteAjaxScroll('.card-container', {
	item: '.item',
	next: '.next',
	pagination: '.pagination'
	});
}

$(document).ready(function(){

	load_previous();

    function load_cart_data() //load specific data
	{
		$.ajax({
			url:"fetch_cart.php",
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				$('#cart_details').html(data.cart_details);
				$('.total_price').text(data.total_price);
				$('.badge').text(data.total_item);
			}
		});
	}
	
	$('#cart-popover').popover({
		html : true,
        container: 'body',
        content:function(){
        	return $('#popover_content_wrapper').html();
        }
	});

	function load_previous(){
		if(localStorage.length == 0){
			var action = 'empty';
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{action:action},
				success:function()
				{
					load_cart_data();
					$('#cart-popover').popover('hide');
				}
			});
		} else {
			for(let i =0; i < localStorage.length; i++){
				var pid = localStorage.key(i);
				var quantity = localStorage.getItem(pid);
				$.ajax({
					url:"fetch_pid.php",
					method:"POST",
					data:
					{	
						pid:pid, 
					},
					success:function()
					{
						load_cart_data();
					}
				});
			}
		}
	}

	function genInvoice() 
	{
		var cart = {};
		for(let i =0; i < localStorage.length; i++) {
			var pid =  localStorage.key(i); //2 3 5 4
			var quantity = localStorage.getItem(pid);
			cart[pid] = quantity;
		}
		console.log(cart);
		$.ajax({
			url:"gen_digest.php",
			method:"POST",
			dataType:'json',
			data: {
				cart:cart,
			},
			success:function(data)
			{	
				alert(data.cart);
				var addform = '<input type="hidden" name="custom" value="' + data.lastId + '"/><input type="hidden" name="invoice" value="'+ data.digest +'" />';
				$('#form1').append(addform);
				// clear handler
				$('#form1').off('submit');
				// actually submit the form
				$('#form1').submit();
			},
			error:(error) => {
				console.log(JSON.stringify(error));
				// alert("Out of Stocks!");
			}
		});
	}

	$('#form1').submit(function(e)
	{ 
		// this code prevents form from actually being submitted
		e.preventDefault();
		e.returnValue = false;
		// some validation code here: if valid, add podkres1 class
		var $form = $(this);
		// this is the important part. you want to submit
		// the form but only after the ajax call is completed
		$.ajax({ 
			type: 'post',
			url: "fetch_cart.php", 
			dataType:"json",
			context: $form, 
			success: function(data) { 
				// your success handler
				$('#form1').append(data.form);
				genInvoice();
			},
			error: function() { // error handler
				alert("submit failed");
			},
			complete: function() { 
				$('#cart-popover').popover('hide');
				localStorage.clear();
			}
		});
   });

	$(document).on('click', '.btn-number-plus', function(){
		var pid = $(this).attr("id").slice(-1);
		var pname = document.getElementById("cart-name" + pid).innerText;
		var price = document.getElementById("cart-price" + pid).innerText;
		var quantity = 1;
		var action = "add";
		if(quantity > 0)
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:
				{	pid:pid,
					name:pname, 
					price:price, 
					quantity:quantity, 
					action:action
				},
				success:function(data)
				{
					load_cart_data();
					alert("Item has been Added into Cart");
					// localStorage.setItem("data_storage", JSON.parse(data_storage));
				}
			});
			if(localStorage.getItem(pid)!= null){
				var oldquan = Number(localStorage.getItem(pid));
				oldquan++;
			}
			if(localStorage.getItem(pid) == 0 || localStorage.getItem(pid) == null){
				localStorage.setItem(pid, quantity);
			}else{
				localStorage.setItem(pid, oldquan);
			}
		}
		else
		{
			alert("lease Enter Number of Quantity");
		}
	});

	$(document).on('click', '.btn-number-minus', function(){
		var pid = $(this).attr("id").slice(-1);
		var pname = document.getElementById("cart-name" + pid).innerText;
		var price = document.getElementById("cart-price" + pid).innerText;
		var quantity = -1;
		var action = "add";
		$.ajax({
			url:"action.php",
			method:"POST",
			data:
			{	pid:pid,
				name:pname, 
				price:price, 
				quantity:quantity, 
				action:action
			},
			success:function(data)
			{
				load_cart_data();
				alert("Item quantity has removed by 1");
			}
		});
		if(localStorage.getItem(pid)!= null){
			var oldquan = Number(localStorage.getItem(pid));
			oldquan--;
		}
		if(oldquan == 0) 
		{
			localStorage.removeItem(pid);
			load_cart_data();
			$('#cart-popover').popover('hide');
			alert("Item has been removed");
		}
		else{
			localStorage.setItem(pid, oldquan);
		}
	});

	$(document).on('click', '.add_to_cart', function(){
		var pid = $(this).attr("id");
		var pname = document.getElementById("name" + pid).innerText;
		var price = document.getElementById("price" + pid).innerText;
		var quantity = 1;
		var action = "add";

		if(quantity > 0)
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:
				{	
					pid:pid,
					name:pname, 
					price:price, 
					quantity:quantity, 
					action:action
				},
				success:function(data)
				{
					load_cart_data();
					alert("Item has been Added into Cart");
				}
			});
			if(localStorage.getItem(pid)!= null){
				var oldquan = Number(localStorage.getItem(pid));
				oldquan++;
			}
			if(localStorage.getItem(pid) == 0 || localStorage.getItem(pid) == null){
				localStorage.setItem(pid, quantity);
			}else{
				localStorage.setItem(pid, oldquan);
			}
		}
		else
		{
			alert("lease Enter Number of Quantity");
		}
	});

	$(document).on('click', '#clear_cart', function(){
		var action = 'empty';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action},
			success:function()
			{
				load_cart_data();
				$('#cart-popover').popover('hide');
				alert("Your Cart has been clear");
				localStorage.clear();
			}
		});
	});
});
