<?php
session_start();
require_once('connection.php');

if(isset($_POST['email']) && isset($_POST['email'])){

    function validate_data( $data ){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);    
        return $data;
    }
}

$uname = validate_data( $_POST['email']);
$pwd = validate_data( $_POST['password']);

// if(empty($uname)){
//     header("Location: index.php? error= Email ID required");
//     exit();
// }
// if(empty($pwd)){
//     header("Location: index.php? error= password required");
//     exit();
// }
// if(isset($_POST['Login'])){
//     if(isset($_POST['email'])){
//         $email = $_POST['email'];
//     }
//     if(isset($_POST['password'])){
//         $password = $_POST['password'];
//     }
//     echo $firstname ." ". $lastname ." ". $phonenumber." ".$email." ".$password  ;
// }

$query = "SELECT * FROM CUSTOMERS WHERE email = '$uname' and password = '$pwd'";
$stid = oci_parse($conn, $query);
$r = oci_execute($stid);
$row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC);
print_r($row);

if(!empty($row)){
    // require_once('products.php');

    $_SESSION['user'] = $uname;
    header("Location: http://localhost/harbour/products.php");
    exit();
}
else{
    $_SESSION['error_message']="Wrong Username or Password";
    header("Location: http://localhost/harbour/index.php");
    exit();
}
?>