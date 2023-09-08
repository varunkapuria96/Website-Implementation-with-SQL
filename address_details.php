<?php 

session_start();

// print_r($_POST);


require_once('connection.php');

    if(isset($_POST['save_changes'])){
        // print("The save_changes is set!!");
        // print_r($_POST);
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
        $update_query ="UPDATE CUSTOMER_ADDRESS
        SET city = '$city',
        country = '$country',
        line1 = '$line1',
        state = '$state',
        tag = '$tag',
        zipcode = '$zipcode'
        WHERE addressid = '$addressid'";
        $stid = oci_parse($conn, $update_query);
        $r = oci_execute($stid);
    }

    if(isset($_POST['delete_address'])){
        //print_r($_POST);
        
        $address_id = $_POST['addressid'];
        $query_delete_address = "DELETE FROM CUSTOMER_ADDRESS WHERE addressid='$address_id'";
        
        $stid_delete_address = oci_parse($conn, $query_delete_address);
        $r_delete_address = oci_execute($stid_delete_address);

    }

    if(isset($_POST['add_address'])){
        //print_r($_POST);
        // if(isset($_POST['addressid'])){
        //     $addressid   = $_POST['addressid'];
        // }
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

        $user_email = $_SESSION['user'];
		$user_query = "SELECT custid from customers WHERE email='$user_email'";
		$stid_user_query = oci_parse($conn, $user_query);
        $r_user_query = oci_execute($stid_user_query);
		$row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
        $cust_id = $row_user_query['CUSTID'];

        $validate_email = $_SESSION['user'];
        $query_add_address = "INSERT into CUSTOMER_ADDRESS(custid, city, country, line1, state, tag, zipcode) 
        VALUES('".$cust_id."','".$city."','".$country."','".$line1."', '".$state."', '".$tag."', '".$zipcode."')";
        
        $stid_add_address = oci_parse($conn, $query_add_address);
        $r_add_address = oci_execute($stid_add_address);
    }

        
    // }

    
    
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
        $address_query = "SELECT c.custid AS custid, addressid, city, country, defaultaddress, line1, state, tag, zipcode
        from customers c join  customer_address ca on c.custid=ca.custid
        where c.email='$name'"; 
        //$query1 = "SELECT * FROM customer_address WHERE email='$name'";
        $stid1 = oci_parse($conn, $address_query);
        $r1 = oci_execute($stid1);
        // $prod_count_array = array();
        while ($row2 = oci_fetch_array($stid1, OCI_RETURN_NULLS+OCI_ASSOC)) {
            $custID = $row2['CUSTID'];
            $addressID = $row2['ADDRESSID'];
            $city = $row2['CITY'];
            $country = $row2['COUNTRY'];
            $defaultaddress = $row2['DEFAULTADDRESS'];
            $line1 = $row2['LINE1'];
            $state = $row2['STATE'];
            $tag = $row2['TAG'];
            $zipcode = $row2['ZIPCODE'];

            
            print '<div class="card" style="width: 50%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
            // print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
            print '<div class="card-body" style="
            display: flex;
            justify-content: space-between;">';
            print '<h6 class="card-header">'.$row2['TAG'].'</h6>';
            print '<div>';
                print '<p class="card-title">'.$row2['LINE1'].'</p>';
                print '<p class="card-title">'. $row2['CITY'].', '.$row2['STATE'].', '.$row2['COUNTRY'].'</p>';
                print '<p class="card-title">Zipcode : '.$row2['ZIPCODE'].'</p>';
            print '</div>'; 

            print '<div>';
            print '<form action="update_address_details.php" method="post">';
            print '<input type="hidden" name="addressid" value='.$row2['ADDRESSID'].'>';
            print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Update Address</button>';
            print '</form>';

            print '<form action="address_details.php" method="post">';
            print '<input type="hidden" name="addressid" value='.$row2['ADDRESSID'].'>';
            print '<button type="submit" name="delete_address" value="delete_address" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Delete Address</button>';
            print '</form>';
            print '</div>'; 

            print '</div>';
            print '</div>';
        }
        print '<form action="add_address.php" method="post">';
        print '<button type="submit" name="add_address" value="add_address" class="btn btn-warning" style="margin-bottom:15px;     width: 216px;
        margin-left: 58%;
        background: #CD2114;
        color: white;">Add New Address</button>';
        print '</form>';
           
    ?>
                       
</div>        
</body>
</html>