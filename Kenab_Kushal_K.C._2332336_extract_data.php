<?php 

//This PHP File is used to send the data past data to the JS Page.

#Including the connection through the connection.php page.
include ('Kenab_Kushal_K.C._2332336_connection.php');
include ('Kenab_Kushal_K.C._2332336_For_Past.php');
include ('Kenab_Kushal_K.C._2332336_get_data.php');

#If there is no data in the WeatherData or Past Data table.
if ($numRows2 == 0) {
    #This pastData function will be called and the data of past week will get stored into the DB from internet.
    pastData($cityname, $cc);
}

function finalPastData($conn,$noOfRow){
    #Deleting every data from the past data table which is not fresh. 
    $del_query = "DELETE FROM WeatherData WHERE NOT Updated_at = CURRENT_DATE();";
    #Selecting all where the city name comes from the URL -> Local Storage.
    $select = "SELECT * FROM WeatherData WHERE city = '{$_GET['cityName']}'"; 
    #Running the del query.
    mysqli_query($conn,$del_query);

    $extractedData = mysqli_query($conn, $select); #Running the query using mysqli_query method.
    $numRows = mysqli_num_rows($extractedData); #Calculating the number of rows.
    $data = array(); #Creating an empty array to store the data
    if ($numRows > 0) { #When number of rows exists in the table.
        while ($row = mysqli_fetch_assoc($extractedData)) { #Using while loop. 
            $data[] = $row; #Appending the data of inside $row variable to $data array.
        }
    } 

    if ($noOfRow > 0){
        $fromDatabase = 'Past Data extracted from the Database. ';
        $data[] = $fromDatabase;
    }
    else{
        $fromInternet = 'Past Data extracted from the Internet. ';
        $data[] = $fromInternet;
    }

    $json = json_encode($data); #Convert the array to JSON
    echo $json; #Echo helps the JS file to fetch the data, as a HTTP Response contains a header and the body. We already have the header function, and now for the body, we need echo.
}
finalPastData($conn,$numRows2);
?>

