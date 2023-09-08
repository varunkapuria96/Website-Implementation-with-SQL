<?php
require_once('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body class="scroll">

<div class="testing">
   <a href="http://localhost/harbour/products.php"><img src="images/2.png" style="
    width: 52%;
"></a>
</div>

<div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content" style="
    padding-top: 15px;
">
                    <div class="form-items">
                        <h3>Register Today</h3>
                        <form class="requires-validation" action="index.php" method="post">

                            <div class="col-md-12">
                                <label for="firstname"><b>First Name</b></label>
                                <input class="form-control" type="text" name="firstname" placeholder="First Name" required>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="lastname"><b>Last Name</b></label>
                                <input class="form-control" type="text" name="lastname" placeholder="Last Name" required>
                               <!-- <div class="valid-feedback">Username field is valid!</div>
                               <div class="invalid-feedback">Username field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="email"><b>Email Address</b></label>
                                <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>
                                 <!-- <div class="valid-feedback">Email field is valid!</div>
                                 <div class="invalid-feedback">Email field cannot be blank!</div> -->
                            </div>

                            <div class="col-md-12">
                                <label for="phonenumber"><b>Phone Number</b></label>
                                <input class="form-control" type="text" name="phonenumber" placeholder="Phone Number" required>
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
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                               <!-- <div class="valid-feedback">Password field is valid!</div>
                               <div class="invalid-feedback">Password field cannot be blank!</div> -->
                           </div>

                            <div class="form-button mt-3">
                                <!-- <button id="submit" type="submit" class="btn btn-primary">Register</button> -->
                                <input class="btn btn-primary" type="submit" name= "Register" value="Sign Up">
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<div>
        <?php
        if(isset($_POST['Register'])){
            if(isset($_POST['firstname'])){
                $firstname   = $_POST['firstname'];
            }
            if(isset($_POST['lastname'])){
                $lastname = $_POST['lastname'];
            }
            if(isset($_POST['phonenumber'])){
                $phonenumber = $_POST['phonenumber'];
            }
            if(isset($_POST['email'])){
                $email = $_POST['email'];
            }
            if(isset($_POST['password'])){
                $password = $_POST['password'];
            }
            $query = "insert into CUSTOMERS(fname, lname, phoneno, email, password) VALUES('".$firstname."','".$lastname."','".$phonenumber."','".$email."','".$password."')";
            $stid = oci_parse($conn, $query);
            $r = oci_execute($stid);
        }

       

        // $query1 = "SELECT * FROM customers";
        // $stid1 = oci_parse($conn, $query1);
        // $r1 = oci_execute($stid1);

        // print '<table border="1">';
        // while ($row = oci_fetch_array($stid1, OCI_RETURN_NULLS+OCI_ASSOC)) {
        // print '<tr>';
        // foreach ($row as $item) {
        //     print '<td>'.($item?htmlentities($item):' ').'</td>';
        // }
        // print '</tr>';
        // }
        // print '</table>';
        ?>
    </div>

</body>
</html>