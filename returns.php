<?php 

session_start();

require_once('connection.php');
    
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

        //$name = $_SESSION['user'];
        $user_email = $_SESSION['user'];
        $user_query = "SELECT custid from customers WHERE email='$user_email'";
        $stid_user_query = oci_parse($conn, $user_query);
        $r_user_query = oci_execute($stid_user_query);
        $row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
        $cust_id = $row_user_query['CUSTID'];

        $returns_query = "SELECT o.orderid, p.productid, p.productname, i.serialnumber ,
        ROUND((current_date - actualdeliverydate),0) as return_period_ending_in
        FROM orders o
        JOIN items_in_orders io ON o.orderid=io.orderid
        JOIN items i ON io.serialnumber=i.serialnumber
        JOIN products p ON i.productid=p.productid
        WHERE (current_date - actualdeliverydate)<=returnperiod
        AND orderstatus ='Delivered' AND custid='$cust_id'
        AND NOT EXISTS
        (
            SELECT orderid, serialnumber
            FROM returns r
            WHERE o.orderid=r.orderid AND io.serialnumber=r.serialnumber
        )";

        //$query1 = "SELECT * FROM customer_address WHERE email='$name'";
        $stid_returns_query = oci_parse($conn, $returns_query);
        $r_returns_query = oci_execute($stid_returns_query);
        // $prod_count_array = array();
        while ($row_returns = oci_fetch_array($stid_returns_query, OCI_RETURN_NULLS+OCI_ASSOC)) {
           
            print '<div class="card" style="width: 50%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
            // print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
            print '<div class="card-body" style="
            display: flex;
            justify-content: space-between;">';
            print '<h6 class="card-header">'.$row_returns['PRODUCTNAME'].'</h6>';
            print '<div>';
                print '<p class="card-title">Serial Number : '.$row_returns['SERIALNUMBER'].'</p>';
                print '<p class="card-title">Days Left : '.$row_returns['RETURN_PERIOD_ENDING_IN'].'</p>';
            print '</div>'; 
            print '</div>';
            print '<form method="post">';
            print '<button type="submit" name="add_address" value="add_address" class="btn btn-primary" style="margin-bottom:15px; width: 216px;
            margin-left: 64%;
            background: #CD2114;
            color: white;">Return Product</button>';
            print '</form>';
            print '</div>';
        }
       
           
    ?>
                       
</div>        
</body>
</html>