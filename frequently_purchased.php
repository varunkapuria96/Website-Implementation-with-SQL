<?php 

session_start();



require_once('connection.php');

    if(isset($_POST['save_changes'])){
        // print("The save_changes is set!!");
        //print_r($_POST);
        if(isset($_POST['addressid'])){
            $addressid   = $_POST['addressid'];
        }
        if(isset($_POST['tag'])){
            $tag   = $_POST['tag'];
        }
        if(isset($_POST['line1'])){
            $line1 = $_POST['line1'];
        }
        if(isset($_POST['city'])){
            $city = $_POST['city'];
        }
        if(isset($_POST['state'])){
            $state = $_POST['state'];
        }
        if(isset($_POST['country'])){
            $country = $_POST['country'];
        }
        if(isset($_POST['zipcode'])){
            $zipcode = $_POST['zipcode'];
        }
        $validate_email = $_SESSION['user'];
        //$query = "insert into CUSTOMERS(fname, lname, phoneno, password) VALUES('".$firstname."','".$lastname."','".$phonenumber."','".$password."')";
        $frequency_query ="with
		order_products as(
		select orderid, p.productname
		from  items_in_orders io join items i on io.serialnumber=i.serialnumber
		join products p on i.productid=p.productid
		),
		ordered_together as(
		select a.productname, b.productname as second_product, count(*) as times_ordered_together,
		row_number() over(partition by a.productname order by count(*) desc) as rn
		from order_products a join order_products b on a.orderid=b.orderid
		where a.productname<>b.productname
		group by a.productname, b.productname
		)
		select productname, second_product, times_ordered_together,
		rn as rank_of_second_product
		from ordered_together
		where rn<=3 and productname='Hose'";
        $stid = oci_parse($conn, $update_query);
        $r = oci_execute($stid);
    }

    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address details</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body class="scroll">

<div style="position:fixed; z-index:999; width: 100%; margin-top: 0px !important;">
	<nav class="navbar navbar-expand-lg navbar-light bg-light" style="justify-content: space-around;">
		<a class="navbar-brand" href="http://localhost/harbour/products.php">
		<img src="images/2.png" width="30" height="30" class="d-inline-block align-top" alt="" style="width: auto; height: 42px;">
		</a>
		<div style="display: flex; justify-content: space-between;">
		<div class="dropdown">
			<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" style="margin-right: 10px; background-color: #CD2114; width: 158px !important;">
			My Account
			</button>
			<div class="dropdown-menu">
				<form action="account_details.php" method="post">
					<?php
					print '<input type="hidden" name="email_validate" value='.$_SESSION['user'].'>';
					?>
				<button class="dropdown-item btn btn-outline-success" type="submit" >Account Details</button>
				</form>
				<form action="address_details.php" method="post">
					<?php
					print '<input type="hidden" name="email_validate" value='.$_SESSION['user'].'>';
					?>
				<button class="dropdown-item btn btn-outline-success" type="submit">Update Address</button>
				</form>
				<form action="my_queries.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">My Queries</button>
				</form>
                <form action="gift_card.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">Gift Card</button>
				</form>
				<form action="returns.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">File a Return</button>
				</form>
				<form action="index.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">Log Out</button>
				</form>
			</div>
		</div>
			<form action="customer_care.php" method="post">
				<button class=" btn btn-secondary btn-outline-success" type="submit" style="color:white; background-color: #CD2114;">Ask Us</button>
			</form>
		</div>
	</nav>
</div>
<div style="padding-top: 50px;">
	</div>

<div style="padding-top:15px;">
    
    <?php

        $name = $_SESSION['user'];
		$productname = $_POST['productname'];
        $address_query = "SELECT c.custid AS custid, addressid, city, country, defaultaddress, line1, state, tag, zipcode
        from customers c join  customer_address ca on c.custid=ca.custid
        where c.email='$name'"; 

		$frequency_query ="WITH
		order_products as(
		select orderid, p.productname
		from  items_in_orders io join items i on io.serialnumber=i.serialnumber
		join products p on i.productid=p.productid
		),
		ordered_together as(
		select a.productname, b.productname as second_product, count(*) as times_ordered_together,
		row_number() over(partition by a.productname order by count(*) desc) as rn
		from order_products a join order_products b on a.orderid=b.orderid
		where a.productname<>b.productname
		group by a.productname, b.productname
		)
		select productname, second_product, times_ordered_together,
		rn as rank_of_second_product
		from ordered_together
		where rn<=3 and productname='$productname'";

        //$query1 = "SELECT * FROM customer_address WHERE email='$name'";
        $stid1 = oci_parse($conn, $frequency_query);
        $r1 = oci_execute($stid1);
        // $prod_count_array = array();
        while ($row2 = oci_fetch_array($stid1, OCI_RETURN_NULLS+OCI_ASSOC)) {

            $product_name = $row2['PRODUCTNAME'];
            $second_product = $row2['SECOND_PRODUCT'];
            $times_ordered_together = $row2['TIMES_ORDERED_TOGETHER'];
            

            print '<form action="update_address_details.php" method="post">';
            print '<div class="card" style="width: 80%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
            // print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
            print '<div class="card-body">';
            print '<h5 class="card-header">'.$row2['SECOND_PRODUCT'].'</h5>';
            print '<h6 class="card-title" style="margin-top:16px; " ><b>It has been ordered <'.$row2['TIMES_ORDERED_TOGETHER'].'> times along with '.$row2['PRODUCTNAME'].' </b></h6>';
            
            print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Buy Now</button>';
            print '</form>';
            print '</div>';
            print '</div>';
        }

    ?>
                       
</div>        
</body>
</html>