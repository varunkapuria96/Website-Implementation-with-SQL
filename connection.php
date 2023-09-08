<?php

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = navydb.artg.arizona.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

// Create connection to Oracle
$conn = oci_connect("mis531groupS1D", "s1dChangeMe#Please", $db);


// ash db
//$conn = oci_connect("anusharaju", "Anusha01", $db);
if (!$conn) {
  
$m = oci_error();
  
echo $m['message'], "\n";
 
exit;

}
else {
  
//print "Connected to Oracle DB!";


$query = 'SELECT * FROM customers';
$stid = oci_parse($conn, $query);
$r = oci_execute($stid);
// echo $r;
// // Fetch the results in an associative array
// print '<table border="1">';
// while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
//    print '<tr>';
//    foreach ($row as $item) {
//       print '<td>'.($item?htmlentities($item):' ').'</td>';
//    }
//    print '</tr>';
// }
// print '</table>';

}

// Close the Oracle connection

oci_close($conn);
?>
