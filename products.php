<?php 
require_once('connection.php');
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

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
				<!-- <form action="orders.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">Orders</button>
				</form>  -->
				<form action="index.php" method="post">
				<button class="dropdown-item btn btn-outline-success" type="submit">Log Out</button>
				</form>
				</form>
				
			</div>
		</div>
			<form action="customer_care.php" method="post">
				<button class=" btn btn-secondary btn-outline-success" type="submit" style="color:white; background-color: #CD2114;">Ask Us</button>
			</form>
		</div>
	</nav>
</div>
<div style=" padding-top: 80px; width: 80%; margin: auto; margin-bottom: 30px;" class="card">
<h6 class="card-header" style="color:#CD2114; display: flex; justify-content: center;"> <b>TOP DEALS ON BEST SELLING PRODUCTS</b></h6>
<div style="display: flex;justify-content: space-evenly; width: 80%; margin: auto;">
<?php
        $query_top = "WITH
		average_times_ordered as(
			select round(avg(TotalNumberOfTimesOrdered),2) as AverageNumberOfTimesOrdered
			from (
			select p.productid, count(*)as TotalNumberOfTimesOrdered
			from items_in_orders io
			join items i on io.serialnumber = i.serialnumber
			join products p on p.productid = i.productid
			group by p.productid)t
			),
		best_selling_products as(
			select p.productid, p.productname, p.priceperunit, count(*) as NumberOfTimesOrdered
			from items_in_orders io
			join items i on io.serialnumber = i.serialnumber
			join products p on p.productid = i.productid
			having count(*) >  (select AverageNumberOfTimesOrdered from average_times_ordered)
			group by p.productid, p.productname, p.priceperunit
			)
		select * from (
			select bsp.productname,bsp.priceperunit, d.discountPercentage, (bsp.priceperunit*d.discountPercentage)/100 as discountValue,
			row_number() over(order by (bsp.priceperunit*d.discountPercentage)/100 desc) as rank_of_values
			from deals_on_products dop
			join best_selling_products bsp on dop.productid = bsp.productid
			join deals d on dop.dealid = d.dealid
			)
		where rank_of_values<=5";
        $stid_top = oci_parse($conn, $query_top);
        $r_top = oci_execute($stid_top);
				while ($row_top = oci_fetch_array($stid_top, OCI_RETURN_NULLS+OCI_ASSOC)) {
				$difference = (int)$row_top['PRICEPERUNIT'] - (int)$row_top['DISCOUNTVALUE'];
				print '<form action="shopping.php" method="post">';
				print '<div class="card text-center" style="width: 100%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
				// print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
				print '<div class="card-body">';
				print '<h5 class="card-title">'.$row_top['PRODUCTNAME'].'</h5>';
				print '<h6 class="card-text" ><b style="color:red;">-%'.$row_top['DISCOUNTPERCENTAGE'].'</b><b>  $'.strval($difference).'</b></h6>';
				print '<p class="card-text">Listprice: <s>$'.$row_top['PRICEPERUNIT']. '</s>  Save upto $'.$row_top['DISCOUNTVALUE'].'</p>';
				
				print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Add to Cart</button>';
				print '</form>';
				print '</div>';
				print '</div>';
				} 
				?>
</div>
</div>

<div style="width: 80%; margin: auto; margin-bottom: 30px;" class="card">

<?php

		// Query to get top products for a season
        $query_top_season = "SELECT * from (
			select
			case
			when extract(month from current_date) in (6,7,8) then 'Summer'
			when extract(month from current_date) in (9,10,11) then 'Fall'
			when extract(month from current_date) in (12,1,2) then 'Winter'
			when extract(month from current_date) in (3,4,5) then 'Spring'
			end as Season,
			productid, count(distinct o.orderid) as total_orders,
			row_number() over(partition by case
			when extract(month from current_date) in (6,7,8) then 'Summer'
			when extract(month from current_date) in (9,10,11) then 'Fall'
			when extract(month from current_date) in (12,1,2) then 'Winter'
			when extract(month from current_date) in (3,4,5) then 'Spring'
			end order by count(distinct o.orderid) desc) as rank_of_product
			from orders o join items_in_orders io on o.orderid=io.orderid
			join items i on io.serialnumber=i.serialnumber
			where
			case
			when extract(month from current_date) in (6,7,8) then 'Summer'
			when extract(month from current_date) in (9,10,11) then 'Fall'
			when extract(month from current_date) in (12,1,2) then 'Winter'
			when extract(month from current_date) in (3,4,5) then 'Spring'
			end=
			case
			when extract(month from orderdate) in (6,7,8) then 'Summer'
			when extract(month from orderdate) in (9,10,11) then 'Fall'
			when extract(month from orderdate) in (12,1,2) then 'Winter'
			when extract(month from orderdate) in (3,4,5) then 'Spring'
			end
			group by case
			when extract(month from current_date) in (6,7,8) then 'Summer'
			when extract(month from current_date) in (9,10,11) then 'Fall'
			when extract(month from current_date) in (12,1,2) then 'Winter'
			when extract(month from current_date) in (3,4,5) then 'Spring'
			end, productid) where rank_of_product<=3
			";
        

		$stid_top_season1 = oci_parse($conn, $query_top_season);
		$r_top_season1 = oci_execute($stid_top_season1);
		$row_top_season_1 = oci_fetch_array($stid_top_season1, OCI_RETURN_NULLS+OCI_ASSOC);
		$season = $row_top_season_1['SEASON'];

		$stid_top_season = oci_parse($conn, $query_top_season);
        $r_top_season = oci_execute($stid_top_season);

print '<h6 class="card-header" style="color:#CD2114; display: flex; justify-content: center;"> <b>Best selling products for <u>'.$season.'</u> Season!!</b></h6>';
?>
<div style="display: flex;justify-content: space-evenly; width: 80%; margin: auto;">
<?php
		//print_r($season);

				while ($row_top_season = oci_fetch_array($stid_top_season, OCI_RETURN_NULLS+OCI_ASSOC)) {
				// $difference = (int)$row_top['PRICEPERUNIT'] - (int)$row_top['DISCOUNTVALUE'];
				print '<form action="shopping.php" method="post">';
				print '<div class="card text-center" style="width: 100%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
				// print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
				print '<div class="card-body">';
				
				$product_id = $row_top_season['PRODUCTID'];
				$que = "SELECT productname, productoverview, priceperunit from products where productid='$product_id'";
				$stid_product_details = oci_parse($conn, $que);
				$r_product_details = oci_execute($stid_product_details);

				$row_product_details = oci_fetch_array($stid_product_details, OCI_RETURN_NULLS+OCI_ASSOC);
				print '<h5 class="card-title">'.$row_product_details['PRODUCTNAME'].'</h5>';
				print '<h6 class="card-title">About : '.$row_product_details['PRODUCTOVERVIEW'].'</h6>';
				print '<h6 class="card-title"><b>Price : $'.$row_product_details['PRICEPERUNIT'].'</b></h6>';
				// print '<h6 class="card-text" ><b style="color:red;">-%'.$row_top['DISCOUNTPERCENTAGE'].'</b><b>  $'.strval($difference).'</b></h6>';
				// print '<p class="card-text">Listprice: <s>$'.$row_top['PRICEPERUNIT']. '</s>  Save upto $'.$row_top['DISCOUNTVALUE'].'</p>';
				
				print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Add to Cart</button>';
				print '</form>';
				print '</div>';
				print '</div>';
				} 
				 ?>
</div>
</div>


<div style="width: 80%; margin: auto; margin-bottom: 30px;" class="card">

<?php
		$user_email = $_SESSION['user'];
		$user_query = "SELECT custid from customers WHERE email='$user_email'";
		$stid_user_query = oci_parse($conn, $user_query);
		$r_user_query = oci_execute($stid_user_query);
		$row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
		$cust_id = $row_user_query['CUSTID'];
		// Query to get top products for a season
        $query_recommended = "WITH all_orders AS (
			SELECT o.orderid, custid, orderdate, productname
			FROM orders o 
			JOIN items_in_orders io ON o.orderid=io.orderid
			JOIN items i ON io.serialnumber=i.serialnumber
			JOIN products p ON i.productid=p.productid
		),
		next_best_product AS (
			SELECT custid, productname , orderid, 
			LEAD(productname) OVER(PARTITION BY custid ORDER BY ORDERDATE) AS NextOrdered,
			LEAD(orderid) OVER(PARTITION BY custid ORDER BY ORDERDATE) AS NextOrderID
			FROM all_orders
		)
		SELECT productname, NextOrdered , count(*) 
		FROM next_best_product 
		WHERE NextOrdered IS NOT NULL AND orderid<>NextOrderID
		AND productname IN
		(
			SELECT productname 
			FROM ORDERS o 
			JOIN items_in_orders io ON o.orderid=io.orderid
			JOIN items i ON io.serialnumber=i.serialnumber
			JOIN products p ON i.productid=p.productid
			WHERE custid='$cust_id' AND
			orderdate =
				(SELECT MAX(orderdate) FROM orders where custid='$cust_id')
		)
		GROUP BY productname, NextOrdered
		ORDER BY COUNT(*) DESC
		FETCH FIRST 3 ROWS ONLY";
        

		$stid_recommended = oci_parse($conn, $query_recommended);
		$r_recommended = oci_execute($stid_recommended);
		//$row_recommended = oci_fetch_array($stid_recommended, OCI_RETURN_NULLS+OCI_ASSOC);
		// $season = $row_recommended['SEASON'];

print '<h6 class="card-header" style="color:#CD2114; display: flex; justify-content: center;"> <b>Recommended Products</b></h6>';
?>
<div style="display: flex;justify-content: space-evenly; width: 80%; margin: auto;">
<?php
		//print_r($season);

				while ($row_recommended2 = oci_fetch_array($stid_recommended, OCI_RETURN_NULLS+OCI_ASSOC)) {
				// $difference = (int)$row_top['PRICEPERUNIT'] - (int)$row_top['DISCOUNTVALUE'];
				print '<form action="shopping.php" method="post">';
				print '<div class="card text-center" style="width: 100%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
				// print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
				print '<div class="card-body">';
				
				// $product_id = $row_recommended['PRODUCTID'];
				// $que = "SELECT productname, productoverview, priceperunit from products where productid='$product_id'";
				// $stid_product_details = oci_parse($conn, $que);
				// $r_product_details = oci_execute($stid_product_details);

				// $row_product_details = oci_fetch_array($stid_product_details, OCI_RETURN_NULLS+OCI_ASSOC);
				print '<h5 class="card-title">'.$row_recommended2['NEXTORDERED'].'</h5>';
				// print '<h6 class="card-text" ><b style="color:red;">-%'.$row_top['DISCOUNTPERCENTAGE'].'</b><b>  $'.strval($difference).'</b></h6>';
				// print '<p class="card-text">Listprice: <s>$'.$row_top['PRICEPERUNIT']. '</s>  Save upto $'.$row_top['DISCOUNTVALUE'].'</p>';
				
				print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Add to Cart</button>';
				print '</form>';
				print '</div>';
				print '</div>';
				} 
				 ?>
</div>
</div>







<div style="padding-top:15px;">
    <?php
		$query2 = 'SELECT p.productid, count(*) AS prod_count
		FROM products p JOIN items i ON p.productid = i.productid
		WHERE availability = 1
		GROUP BY p.productid
		HAVING count(*) <= 5';

        $stid2 = oci_parse($conn, $query2);
        $r2 = oci_execute($stid2);

		while ($row2 = oci_fetch_array($stid2, OCI_RETURN_NULLS+OCI_ASSOC)) {
		$prod_count_array[ $row2['PRODUCTID'] ] = $row2['PROD_COUNT'];
		}

		$review_query = "SELECT rd.productid, round(avg(ratings),2) as avg_rating,count(distinct reviewid) as review_count
		from review_details rd join orders o on rd.custid=o.custid
		join items_in_orders io on o.orderid=io.orderid
		join items i on io.serialnumber=i.serialnumber
		group by rd.productid
		having count(distinct reviewid)>=5";
		$stid_review = oci_parse($conn, $review_query);
        $r_review = oci_execute($stid_review);
		$review_array = array();
		while ($row_review = oci_fetch_array($stid_review, OCI_RETURN_NULLS+OCI_ASSOC)) {
			$review_array[$row_review['PRODUCTID']] = $row_review['AVG_RATING'];
		}
		// print_r($review_array);
		// print_r($prod_count_array);

		// query to find the customer ID
		$user_email = $_SESSION['user'];
		$user_query = "SELECT custid from customers WHERE email='$user_email'";
		$stid_user_query = oci_parse($conn, $user_query);
        $r_user_query = oci_execute($stid_user_query);
		$row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
		

		// query to get all the products
		$query1 = 'SELECT productid, productname, productoverview, priceperunit FROM products';
        $stid1 = oci_parse($conn, $query1);
        $r1 = oci_execute($stid1);

				while ($row = oci_fetch_array($stid1, OCI_RETURN_NULLS+OCI_ASSOC)) {
				
				print '<form action="shopping.php" method="post">';
				print '<div class="card" style="width: 80%; margin: auto; margin-top: 20px;">';
				print '<h6 class="card-header">'.$row['PRODUCTNAME'].'</h6>';
				print '<div class="card-body">';
				print '<h6 class="card-title">'.$row['PRODUCTOVERVIEW'].'</h6>';
				print '<h6 class="card-text"><b>Price : $'.$row['PRICEPERUNIT'] .'</b></h6>';
				if( !empty( $review_array[$row['PRODUCTID']])){
					print '<h6 class="card-title" style=""><b> Ratings :  '.$review_array[$row['PRODUCTID']].' out of 5 </b></h6>';
					
				}
				if( !empty( $prod_count_array[$row['PRODUCTID']])){
					print '<p class="card-title" style="color:#CD2114;"><i>( Hurry Up!! Only '.$prod_count_array[$row['PRODUCTID']].' items left!! )</i></p>';
					
				}

				$cust_id = $row_user_query['CUSTID'];
				$prod_id = $row['PRODUCTID'];

				//query to check whether customer is eligible for free shipping
				$free_delivery_query ="SELECT case when avail>0 then 1 else 0 end as Eligible from (
					SELECT count(*) as avail
					from customer_address ca
					join stores s on ca.zipcode=s.zipcode
					join items i on s.storeid=i.storeid
					join products p on i.productid=p.productid
					where ca.custid='$cust_id' and availability=1 and p.productid='$prod_id'
					) x";
				$stid_free_delivery = oci_parse($conn, $free_delivery_query);
				$r_free_delivery = oci_execute($stid_free_delivery);

				// $free_delivery
				$row_free_delivery = oci_fetch_array($stid_free_delivery, OCI_RETURN_NULLS+OCI_ASSOC);
				
				if( $row_free_delivery['ELIGIBLE'] ){
					print '<p class="card-title" style="color:green;"><i>( This item is eligible for free delivery!! )</i></p>';
					
				}
				print '<input type="hidden" name="productid" value='.$row['PRODUCTID'].'>';
				print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Add to Cart</button>';
				print '</form>';
				print '<form action="frequently_purchased.php" method="post">';
				print '<input type="hidden" name="productid" value='.$row['PRODUCTID'].'>';
				print '<input type="hidden" name="productname" value='.$row['PRODUCTNAME'].'>';
					?>
					<button type="submit" name="frequentlypurchased" value="addtocart" class="btn btn-warning" style="width: 220px;">Frequently Purchased With</button>
					</form>
				<?php
				
				print '</div>';
				print '</div>';
				} ?>
				</div>
	<script>
		
		const targetDiv = document.getElementById("third");
		const btn = document.getElementById("toggle");
		btn.onclick = function () {
		if (targetDiv.style.display !== "none") {
			targetDiv.style.display = "none";
		} else {
			targetDiv.style.display = "block";
		}
		};
  	</script>
            
</body>
</html>