<?php
session_start();


// if(isset($_POST['addtocart'])){
//     if( empty( $cart_array ) ){
//         $cart_array = array($_POST['productid']);
//     }
//     else{
//         array_push($cart_array, $_POST['productid']);
//     }
// }

// print_r($cart_array);

if(isset($_POST['addtocart'])){
    //print_r($_POST['productid']);

    // if(isset($_SESSION['cart'])){
    //     print("session already created!");
    //     array_push($_SESSION['cart'], $_POST['productid']);
    //     print_r($_SESSION['cart']);
    // } else {
    //     $item_array = array(
    //         'product_id'=> $_POST['productid']
    //     );

    //     $_SESSION['cart'][0] = $item_array;
    //     print("new session created");
    //     print_r($_SESSION['cart']);
    // }


    if(isset($_SESSION['cart1'])){
        print("session already created!");
        
        //array_push($_SESSION['cart'], $_POST['productid']);
        $item_array_id = array_column($_SESSION['cart1'], "product_id");

        if(in_array($_POST["productid"], $item_array_id)){
            echo "<script>alert('Product already added')</script>";
            echo "<script>window.location = 'products.php'</script>";
        }else{
            $count = count($_SESSION['cart1']);
            $item_array = array(
                'product_id'=> $_POST['productid']
            );
            $_SESSION['cart1'][$count] = $item_array;
        }
        print_r($_SESSION['cart1'][0]);
        print_r(array_column($_SESSION['cart1'], "product_id"));

    } else {
        $item_array = array(
            'product_id'=> $_POST['productid']
        );

        $_SESSION['cart1'][0] = $item_array;
        print("new session created");
        print_r(array_column($_SESSION['cart1'][0], "product_id"));

    }
// ?>
<!-- //     <div style="padding-top: 100px;">
        
//         $query1 = 'SELECT productid, productname, productoverview, priceperunit FROM products WHERE productid = '.$_SESSION['cart'].';
//         $stid1 = oci_parse($conn, $query1);
//         $r1 = oci_execute($stid1);
// 				while ($row = oci_fetch_array($stid1, OCI_RETURN_NULLS+OCI_ASSOC)) {
				
// 				print '<form action="shopping.php" method="post">';
// 				print '<div class="card" style="width: 80%; margin: auto; margin-top: 20px;">';
// 				print '<h6 class="card-header">'.$row['PRODUCTNAME'].'</h6>';
// 				$prodid = $row['PRODUCTID'];
// 				print '<div class="card-body">';
// 					print '<p class="card-title">'.$row['PRODUCTOVERVIEW'].'</p>';
// 					print '<h6 class="card-text">PRICE : $'.$row['PRICEPERUNIT'] .'</h6>';
// 					print '<input type="hidden" name="productid" value='.$row['PRODUCTID'].'>';
					?>
					
					 <button type="submit" name="addtocart" value="addtocart" class="btn btn-warning">Add to Cart</button>
					</form>
					
				</div>
				</div>
				<?php //} ?>
				</div> -->
<?php
}

if(isset($_POST['clearsession'])){
    session_unset();
    session_destroy();
}

?>
<form action="products.php" method="post">
<button type="submit" name="addmoretocart" value="addmoretocart" class="btn btn-warning">Add more Products!</button>
</form>
<form action="shopping.php" method="post">
<button type="submit" name="clearsession" value="clearsession" class="btn btn-warning">Clear sessions!</button>
</form>