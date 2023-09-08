<?php 

session_start();

// print_r($_POST);


require_once('connection.php');

$user_email = $_SESSION['user'];
$user_query = "SELECT custid from customers WHERE email='$user_email'";
$stid_user_query = oci_parse($conn, $user_query);
$r_user_query = oci_execute($stid_user_query);
$row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
$cust_id = $row_user_query['CUSTID'];

// print_r($cust_id);

if(isset($_POST['submit_review'])){

    $product_review = $_POST['product_review'];
    $product_name = $_POST['product_name'];

    if(isset($_POST['product_review'])){
        //print_r($_POST);
        
        $address_id = $_POST['addressid'];
        $query_add_address = "INSERT into CUSTOMER_ADDRESS(custid, city, country, line1, state, tag, zipcode) 
        VALUES('".$cust_id."','".$city."','".$country."','".$line1."', '".$state."', '".$tag."', '".$zipcode."')";
        
        $stid_add_address = oci_parse($conn, $query_add_address);
        $r_add_address = oci_execute($stid_add_address);
    
    }

    // if(isset($_POST['updated_product_review'])){
    //     //print_r($_POST);
        
    //     $address_id = $_POST['addressid'];
    //     $query_delete_address = "DELETE FROM CUSTOMER_ADDRESS WHERE addressid='$address_id'";
        
    //     $stid_delete_address = oci_parse($conn, $query_delete_address);
    //     $r_delete_address = oci_execute($stid_delete_address);
    
    // }
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
                <!-- <form action="returns.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">File a Return</button>
				</form> -->
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
        
        $query_review = "SELECT distinct p.productid, productname from
        orders o join items_in_orders io on o.orderid=io.orderid
        join items i on io.serialnumber=i.serialnumber
        join products p on i.productid=p.productid
        where custid='$cust_id'";

        $stid_query_review = oci_parse($conn, $query_review);
        $r_query_review = oci_execute($stid_query_review);
        // $prod_count_array = array();
        while ($row_r = oci_fetch_array($stid_query_review, OCI_RETURN_NULLS+OCI_ASSOC)) {
            
            print '<div class="card" style="width: 50%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
            // print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
            print '<div class="card-body" style="
            display: flex;
            justify-content: space-between;">';
                print '<h6 class="card-header">'.$row_r['PRODUCTNAME'].'</h6>';
                print '<div>';
                if( isset($product_name) &&  $product_name == $row_r['PRODUCTNAME']){
                    ?>
                    <div class="col-md-12">
                                <label for="email"><b>Review</b></label>
                                <?php
                                print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$product_review.'</p>';
                                print '<form action="write_review.php" method="post">';
                                print '<input type="hidden" name="productname" value='.$row_r['PRODUCTNAME'].'>';
                                print '<button type="submit" name="update_review" value="update_review" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Update Review</button>';
                                print '</form>';
                                ?>
                            </div>
                    <?php
                } else {
                    print '<form action="write_review.php" method="post">';
                    print '<input type="hidden" name="productname" value='.$row_r['PRODUCTNAME'].'>';
                    print '<button type="submit" name="write_review" value="write_review" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Write Review</button>';
                    print '</form>';
                }
                print '</div>'; 
            print '</div>';
            print '</div>';
        }
       
           
    ?>
                       
</div>        
</body>
</html>