<?php 
session_start();
// print_r( $_SESSION['user_email']);
require_once('connection.php');
$code_invalid = FALSE;
$code_valid = FALSE;

// print_r($_SESSION['user']);

if(isset($_POST['submit_gift_card'])){
    // print_r($_POST['gift_card']);
    $code = $_POST['gift_card'];
    $query_gift = "SELECT * FROM gift_cards WHERE giftcardcode='$code' AND isactivated='No'";
    $stid_query_gift = oci_parse($conn, $query_gift);
    $r_query_gift = oci_execute($stid_query_gift);
    
    if($row_query_gift = oci_fetch_array($stid_query_gift, OCI_RETURN_NULLS+OCI_ASSOC)) {
        // print("Gift card valid!!");
        
        // header("Location: http://localhost/harbour/products.php");
        // exit();
        $user_email = $_SESSION['user'];
		$user_query = "SELECT custid from customers WHERE email='$user_email'";
		$stid_user_query = oci_parse($conn, $user_query);
        $r_user_query = oci_execute($stid_user_query);
		$row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
        $cust_id = $row_user_query['CUSTID'];

        $serial_number = $row_query_gift['SERIALNUMBER'];
        $code_amount = $row_query_gift['AMOUNT'];
        // // query to gte serial number of gift card
        // $serial_query = "SELECT serialnumber from gift_cards WHERE giftcardcode='$code'";
		// $stid_serial_query = oci_parse($conn, $serial_query);
        // $r_serial_query = oci_execute($stid_serial_query);
		// $row_serial_query = oci_fetch_array($stid_serial_query, OCI_RETURN_NULLS+OCI_ASSOC);
        // $cust_id = $row_serial_query['CUSTID'];

        $query_code = "INSERT into gift_card_activation(serialnumber, custid, dateofactivation) VALUES('".$serial_number."','".$cust_id."', to_date(to_char(sysdate, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi'))";
        $stid_query_code = oci_parse($conn, $query_code);
        $r_query_code = oci_execute($stid_query_code);

        $query_code1 = "UPDATE gift_cards
        SET isactivated = 'Yes'
        WHERE giftcardcode='$code'";
        $stid_query_code1 = oci_parse($conn, $query_code1);
        $r_query_code1 = oci_execute($stid_query_code1);

        $code_valid = TRUE;

    }else{
        // print("Gift card invalid!!");
        $code_invalid = TRUE;
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Care</title>
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
<div style="padding-top: 0px;">

<?php
        $name = $_SESSION['user'];
        $query1 = "SELECT * FROM customers WHERE email='$name'";
        $stid1 = oci_parse($conn, $query1);
        $r1 = oci_execute($stid1);
        $prod_count_array = array();
        while ($row2 = oci_fetch_array($stid1, OCI_RETURN_NULLS+OCI_ASSOC)) {
            $fname = $row2['FNAME'];
            $lname = $row2['LNAME'];
            $phoneno = $row2['PHONENO'];
            $email = $row2['EMAIL'];
            $password = $row2['PASSWORD'];
            $wallet_balance = $row2['WALLETBALANCE'];
        }
        //print_r($prod_count_array);


			?>

<div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                    <?php
                        print '<h3 style="margin-bottom:16px;">Hi '.$fname.'</h3>';
                        ?>
                        <form class="requires-validation" action="gift_card.php" method="post">

                            <div class="col-md-12">
                                <label for="customer_query"><b>Paste your gift card details below.</b></label>
                                <br>
                                <?php
                                
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$fname.'</p>';
                                print '<input class="form-control" type="text" name="gift_card" required></input>';
                                ?>

                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                     
                 
                     


                            <div class="form-button mt-3">
                                <!-- <button id="submit" type="submit" class="btn btn-primary">Register</button> -->
                                <input class="btn btn-primary" type="submit" name= "submit_gift_card" value="Submit">
                            </div>

                            <?php
                            if( $code_invalid ){
                                print '<div id="box"><h5>Error: Gift card already activated!</h5></div>';
                            }
                            if( $code_valid ){
                                print '<div id="box"><h5>Amount of $'.$code_amount.' added to your Wallet</h5></div>';
                            }
                            ?>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>        
<script src="index.js"></script>
</body>
</html>