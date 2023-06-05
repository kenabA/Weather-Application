<?php

include ('Kenab_Kushal_K.C._2332336_connection.php');

$cityname = $_GET["cityName"];

#Using select query again to select all the past data from the WeatherData table.
$select2 = "SELECT * FROM WeatherData WHERE city='{$cityname}' AND Updated_at = CURRENT_DATE();"; 
#Running the query.
$secondResult = mysqli_query($conn,$select2);
#Checking the number of rows.
$numRows2 = mysqli_num_rows($secondResult); 

?>