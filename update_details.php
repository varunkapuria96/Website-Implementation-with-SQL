<?php 
session_start();
// print_r( $_SESSION['user_email']);
require_once('connection.php');
// if(isset($_POST['email_validate'])){
//     print_r($_POST['email_validate']);
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update details</title>
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
        print_r($prod_count_array);
			?>

<div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                    <?php
                        print '<h3 style="margin-bottom:16px;">Hi '.$fname.'</h3>';
                        ?>
                        <form class="requires-validation" action="account_details.php" method="post">

                            <div class="col-md-12">
                                <label for="firstname"><b>First Name</b></label>
                                <?php
                                
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$fname.'</p>';
                                print '<input class="form-control" type="text" name="firstname" placeholder='.$fname.' required>';
                                ?>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="lastname"><b>Last Name</b></label>
                                <?php
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$lname.'</p>';
                                print '<input class="form-control" type="text" name="lastname" placeholder='.$lname.' required>';
                                ?>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="email"><b>Email Address</b></label>
                                <?php
                                print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$email.'</p>';
                                //print '<input class="form-control" type="email" name="email" placeholder='.$email.' required>';
                                ?>
                                 <!-- <div class="valid-feedback">Email field is valid!</div>
                                 <div class="invalid-feedback">Email field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="phonenumber"><b>Phone Number</b></label>
                                <?php
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$phoneno.'</p>';
                                print '<input class="form-control" type="text" name="phonenumber" placeholder='.$phoneno.' required>';
                                ?>
                                 <!-- <div class="valid-feedback">Email field is valid!</div>
                                 <div class="invalid-feedback">Email field cannot be blank!</div> -->
                            </div>
                        <!-- 
                           <div class="col-md-12">
                                <select class="form-select mt-3" required>
                                      <option selected disabled value="">Position</option>
                                      <option value="jweb">Junior Web Developer</option>
                                      <option value="sweb">Senior Web Developer</option>
                                      <option value="pmanager">Project Manager</option>
                               </select>
                                <div class="valid-feedback">You selected a position!</div>
                                <div class="invalid-feedback">Please select a position!</div> 
                           </div> -->


                           <div class="col-md-12">
                                <label for="password"><b>Password</b></label>
                                <?php
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$password.'</p>';
                                print '<input class="form-control" type="password" name="password" placeholder='.$password.' required>';
                               
                                ?>
                               <!-- <div class="valid-feedback">Password field is valid!</div>
                               <div class="invalid-feedback">Password field cannot be blank!</div> -->
                           </div>

<!-- 
                           <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" id="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" id="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>

                            <input type="radio" class="btn-check" name="gender" id="secret" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="secret">Secret</label>
                               <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div> -->

                        <!-- <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                          <label class="form-check-label">I confirm that all data are correct</label>
                         <div class="invalid-feedback">Please confirm that the entered data are all correct!</div>
                        </div> -->
                  

                            <div class="form-button mt-3">
                                <!-- <button id="submit" type="submit" class="btn btn-primary">Register</button> -->
                                <input class="btn btn-primary" type="submit" name= "save_changes" value="Save changes">
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>        
</body>
</html>