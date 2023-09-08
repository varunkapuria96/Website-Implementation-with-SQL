
<?php

require_once('connection.php');
// unset($_SESSION['user_email']);
// if(isset($_SESSION['error_message'])){
//     echo $_SESSION['error_message'];
//  }
//  else{
    if(isset($_SESSION)){
        session_destroy();
        }
//  }

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



require_once('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body class="scroll">
<div class="testing">
<img src="images/2.png" >
</div>
<div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content" style="padding-top: 15px !important;">
                    <div class="form-items">
                        <h3>Sign In to My Account</h3>
                        
                        <form class="requires-validation" action="login.php" method="post">

                            <div class="col-md-12">
                                <label for="email"><b>Email Address</b></label>
                                <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>
                                 <!-- <div class="valid-feedback">Email field is valid!</div>
                                 <div class="invalid-feedback">Email field cannot be blank!</div> -->
                            </div>

                           <div class="col-md-12">
                                <label for="password"><b>Password</b></label>
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                               <!-- <div class="valid-feedback">Password field is valid!</div>
                               <div class="invalid-feedback">Password field cannot be blank!</div> -->
                           </div>

                            <div class="form-button mt-3">
                                <!-- <button id="submit" type="submit" class="btn btn-primary">Register</button> -->
                                <input class="btn btn-primary" type="submit" name= "Login" value="Login">
                                <a href="my_registration.php" style="margin-right: 15px;"> New User? Create account</a>
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>