<?php 
//This PHP File is used to simply create a connection between the data base and the PHP File.
  $username = "epiz_34161223";
  $password = "KzGJZF8XDbBWCXc";
  $servername = "sql104.epizy.com";
  $dbName = "epiz_34161223_Weather_Database";

  $conn = mysqli_connect($servername,$username,$password,$dbName);

  if (!$conn){
    echo "Failed to Connect to the Database.";
  }

?>

