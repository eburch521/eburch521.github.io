<?php   
include("connDB.php");
$conn = connDB();
$query = "DROP DATABASE MerchStoreDB;";
mysqli_query($conn,$query);
echo "<h1>Database Erased.</h1>";
mysqli_close($conn);
?>
<?php 
    include("merchstoredb.php"); 
    include("populatedb.php");
?>
