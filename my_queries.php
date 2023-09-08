<?php 

session_start();

require_once('connection.php');

if(isset($_POST['delete_query'])){
            
    // query to delete the selected query
    $delete_query_id = $_POST['deletequeryid'];
    $query_delete_query = "DELETE FROM CUSTOMER_CARE WHERE custqueryid='$delete_query_id'";
    
    $stid_delete_query = oci_parse($conn, $query_delete_query);
    $r_delete_address = oci_execute($stid_delete_query);

}

    if(isset($_POST['submit_query'])){
        // print("The save_changes is set!!");
        // print_r($_POST);

        
    

        if(isset($_POST['updated_query'])){
            $cust_query_id = $_POST['custqueryid'];
            $updated_query = $_POST['updated_query'];

            // to update the query
            $update_query = "UPDATE CUSTOMER_CARE
            SET description = '$updated_query'
            WHERE custqueryid = '$cust_query_id'";
            $stid_update_query = oci_parse($conn, $update_query);
            $r_update_query = oci_execute($stid_update_query);
        }
        if(isset($_POST['customer_query'])){
            $customer_query   = $_POST['customer_query'];
            $user_email = $_SESSION['user'];
            $user_query = "SELECT custid from customers WHERE email='$user_email'";
            $stid_user_query = oci_parse($conn, $user_query);
            $r_user_query = oci_execute($stid_user_query);
            $row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
            $cust_id = $row_user_query['CUSTID'];

            // query to insert into customer_care table
            $query_customer_care = "INSERT into CUSTOMER_CARE(description, custid, querydate) VALUES('".$customer_query."','".$cust_id."', to_date(to_char(sysdate, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi'))";
            $stid_query_customer_care = oci_parse($conn, $query_customer_care);
            $r_query_customer_care = oci_execute($stid_query_customer_care);

            // query to get the query ID
            $que_id = "SELECT custqueryid from (
                select custid, custqueryid , row_number() over(partition by custid order by querydate desc) as rn
                from CUSTOMER_CARE
                where custid='$cust_id' and status='Open' and agentID is null)
                where rn=1";
            $stid_que_id = oci_parse($conn, $que_id);
            $r_que_id = oci_execute($stid_que_id);
            $row_que_id = oci_fetch_array($stid_que_id, OCI_RETURN_NULLS+OCI_ASSOC);
            $query_ID = $row_que_id['CUSTQUERYID'];
            
            // query to assign agent to a new query
            $query_ID = intval($query_ID);
            $que_procedure1 = "BEGIN update_AgentID('$query_ID'); END;";
            $stid_procedure1 = oci_parse($conn, $que_procedure1);
            $r_procedure1 = oci_execute($stid_procedure1);
        }
        
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
        $user_email = $_SESSION['user'];
		$user_query = "SELECT custid from customers WHERE email='$user_email'";
		$stid_user_query = oci_parse($conn, $user_query);
        $r_user_query = oci_execute($stid_user_query);
		$row_user_query = oci_fetch_array($stid_user_query, OCI_RETURN_NULLS+OCI_ASSOC);
        $cust_id = $row_user_query['CUSTID'];

        $cc_query = "SELECT cc.*, agentfname, ResolutionTimeinHours
                    from customer_care cc join
                    (select cc.agentid, agentfname, coalesce(round(avg((resolutiondate-querydate)*24)),0) as ResolutionTimeinHours
                    from customer_care cc
                    join agents a on a.agentid = cc.agentid
                    group by cc.agentid, agentfname
                    order by ResolutionTimeinHours) ag on cc.agentid=ag.agentid
                    where custid='$cust_id' order by querydate DESC"; 
        //$query1 = "SELECT * FROM customer_address WHERE email='$name'";
        $stid_cc_query = oci_parse($conn, $cc_query);
        $r_cc_query = oci_execute($stid_cc_query);
        // $prod_count_array = array();


        while ($row2 = oci_fetch_array($stid_cc_query, OCI_RETURN_NULLS+OCI_ASSOC)) {
            
            if( $row2['RESOLUTIONTIMEINHOURS'] > 100){
                $new_resolution_time = round($row2['RESOLUTIONTIMEINHOURS']/10);
            }else{
                $new_resolution_time = $row2['RESOLUTIONTIMEINHOURS'];
            }

            print '<form action="update_address_details.php" method="post">';
            print '<div class="card" style="width: 80%; margin: auto; margin-top: 20px; margin-bottom: 20px">';
            // print '<h6 class="card-header">'.$row_top['PRODUCTNAME'].'</h6>';
            print '<div class="card-body">';
            print '<h6 class="card-header" style="margin-bottom: 10px;">'.$row2['STATUS'].'</h6>';
            print '<p class="card-title"><b>Query : </b>'.$row2['DESCRIPTION'].'</p>';
            //print '<p class="card-title">Agent Assigned'. $row2['CITY'].', '.$row2['STATE'].', '.$row2['COUNTRY'].'</p>';
            print '<p class="card-title"><b>Date created : </b>'.$row2['QUERYDATE'].'</p>';
            print '<p class="card-title"><b>Agent assigned : </b>'.$row2['AGENTFNAME'].'</p>';
            print '<p class="card-title" style="color:green;"><i>(Your Query will be resolved in the next <b>'.$new_resolution_time.'</b> Hrs.)</i></p>';
            //print '<button type="submit" name="addtocart" value="addtocart" class="btn btn-warning" style="margin-bottom:15px; width: 220px;">Update Address</button>';
            print '<textarea id="third" class="form-control" type="text" name="customer_query" style="
                                margin-top: 10px;
                                background: #F8F9FA;
                                display: none;
                            " required></textarea>'; 
            print '</form>';

            print '<form action="update_query.php" method="post">';
            print '<input class="form-control" type="hidden" name="custqueryid" value='.$row2['CUSTQUERYID'].' required>';
            print '<input id="toggle" class="btn btn-primary" type="submit" name= "edit_query" value="Edit Query" style="margin-bottom: 0px;">';
            print '</form>';

            print '<form action="my_queries.php" method="post">';
            print '<input type="hidden" name="deletequeryid" value='.$row2['CUSTQUERYID'].'>';
            print '<input id="toggle" class="btn btn-primary" type="submit" name= "delete_query" value="Delete Query" style="margin-bottom: 0px;">';
            print '</form>';
            print '</div>';
            print '</div>';
        }

    ?>
                       
</div>   

</body>
</html>