 <?php
$connection = mysqli_connect("localhost", "root", "", "foodwaste_db1");


// Check connection
if (!$connection) {
die("Connection failed: " . mysqli_connect_error());
}
?>