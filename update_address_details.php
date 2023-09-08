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
    <title>Update Address details</title>
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
        $addressid = $_POST['addressid'];
        $address_query = "SELECT c.custid AS custid, addressid,fname,city, country, defaultaddress, line1, state, tag, zipcode
        from customers c join  customer_address ca on c.custid=ca.custid
        where addressid='$addressid'";
        $stid1 = oci_parse($conn, $address_query);
        $r1 = oci_execute($stid1);
        $prod_count_array = array();
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
        }
        //print_r($prod_count_array);
        
        
?>

<div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                    <?php
                        //print '<h3 style="margin-bottom:16px;">Hi '.$fname.'</h3>';
                        ?>
                        <form class="requires-validation" action="address_details.php" method="post">
                            <?php
                            $states_array = array('AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY');
                            $i=0;
                            
                            ?>
                            <div class="col-md-12">
                                <label for="tag"><b>Tag</b></label>
                                <?php
                                        
                                ?>
                                <select class="form-select mt-3" name ="tag" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'required>
                                      <option selected disabled value="">Select a Tag</option>
                                      <option value="Home">Home</option>
                                      <option value="Work">Work</option>
                                      <option value="Other">Other</option>
                               </select>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>
                            <div class="col-md-12">
                                
                                <div class="valid-feedback">You selected a position!</div>
                                <div class="invalid-feedback">Please select a position!</div> 
                           </div>

                            <div class="col-md-12">
                                <label for="line1"><b>Address Line 1</b></label>
                                <?php
                                
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$fname.'</p>';
                                print '<input class="form-control" type="text" name="line1" placeholder='.$line1.' required>';
                                ?>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="city"><b>City</b></label>
                                <?php
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$lname.'</p>';
                                print '<input class="form-control" type="text" name="city" placeholder='.$city.' required>';
                                ?>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="state"><b>State</b></label>
                                <?php
                               // print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$state.'</p>';
                               
                                ?>
                                <select class="form-select mt-3" name ="state" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'required>
                                      <option selected disabled value="">Select a State</option>
                                      <?php
                                        $i=0;
                                        while($i < count($states_array)) {
                                        print '<option value='.$states_array[$i].'>'.$states_array[$i].'</option>';
                                        $i = $i +1;
                                        }
                                      ?>
                                      <!-- <option value="Work">Work</option>
                                      <option value="Other">Other</option> -->
                                </select>
                                 <!-- <div class="valid-feedback">Email field is valid!</div>
                                 <div class="invalid-feedback">Email field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="country"><b>Country</b></label>
                                <?php
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$phoneno.'</p>';
                                print '<input class="form-control" type="text" name="country" placeholder='.$country.' required>';
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
                                <label for="zipcode"><b>Zipcode</b></label>
                                <?php
                                //print '<p class="form-control" style="background-color: #e9edf0; margin-top:16px;" >'.$password.'</p>';
                                print '<input class="form-control" type="text" name="zipcode" placeholder='.$zipcode.' required>';
                                print '<input class="form-control" type="hidden" name="custid" value='.$custID.' required>';
                                print '<input class="form-control" type="hidden" name="addressid" value='.$addressID.' required>';
                               
                                ?>
                               <!-- <div class="valid-feedback">Password field is valid!</div>
                               <div class="invalid-feedback">Password field cannot be blank!</div> -->
                            </div>
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