<?php

//This PHP File is used to get the current data ( Default or User's Choice ) and send it to JS.

include("Kenab_Kushal_K.C._2332336_connection.php");

#The variable '$cityname' gets the name of the cityName through the URL which was fetched from the JS File.
$cityname = $_GET["cityName"];

#Making a function to get the country code in order to pass it into the API.
function countryCode($city) {
  $apiKey = '6b16146e9f69b9e6c149f2c1e56fb165';
  $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}"; #URL of the api, which takes in the city name and API key.

  #Using CURL to fetch the data from the API.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  
  #Converting JSON into an Associative Array.
  $data = json_decode($response, true);
  
  #Getting the country code.
  $code = $data['sys']['country'];

  #Returning the country code.
  return $code;
}

#Storing the country code into the cc variable.
$cc = countryCode($cityname);

#Makking a function to fetchData of the current or required city.
function fetchData($cityname,$conn) {

    $apiKey = '6b16146e9f69b9e6c149f2c1e56fb165'; #My API Key
    $url = "https://api.openweathermap.org/data/2.5/weather?q=${cityname}&appid={$apiKey}"; #URL of the api, which takes in the city name and the apikey.
    
    #Fetching the API using CURL.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responses = curl_exec($ch);
    curl_close($ch);
    
    #Converting JSON into an Associative Array.
    $data = json_decode($responses, true);

    #Incase of any errors, we return "Error" to the JavaScript file and the code below won't run.
    $responseCode = $data['cod'];
    if ($responseCode == 400 || $responseCode == 404) {
        echo "Error";
        return;
    }
    
    #Extracting all the data from the associative array.
    $Name = $data['name'];
    $Country = $data['sys']['country'];
    $Fullname = "{$Name}, {$Country}"; # Joining city name and country code
    $Temperature = intval(($data['main']['temp']) - 273.15); #Converting Kelvin into celcius.
    $Humidity = $data['main']['humidity'];
    $Windspeed = intval($data['wind']['speed']);
    $Pressure = $data['main']['pressure'];
    $Icon = $data['weather'][0]['icon'];
    $Description = $data['weather'][0]['description'];
    $Date = date('m-d-Y',$data['dt']); #Getting the date in a desired format using date() function.
  
    #Inserting all the required data into the database. 
    $sqli = "INSERT INTO CurrentWeather (Name, Temperature, Humidity, Pressure, Windspeed, Description, Date_Time, Icon) VALUES ('$Fullname', '$Temperature', '$Humidity', '$Pressure', '$Windspeed', '$Description','$Date','$Icon')";

    #Running the mysqli_query method to execute the query.
    $result = mysqli_query($conn,$sqli);
  
}

#Using the select query to select all the data from the CurrentWeather Table.
$select = "SELECT * FROM CurrentWeather WHERE name='{$cityname}, {$cc}' AND updated_at = CURRENT_DATE();"; 
#Running the select SQL
$firstResult = mysqli_query($conn, $select);
#Calculating the number of rows from the current data table.
$numRowsOne = mysqli_num_rows($firstResult); 

#When there is no data or records of the desired City:
if ($numRowsOne == 0) {
  #Calling the fetchData function which basically fetches the data from the internet.
  fetchData($cityname,$conn);
}

#Making a function for selecting the data from the database.
function currentData($city, $countryCode, $noOfRow, $conn){

  #Deleting every past data from the database since we only require the current data.
  $del_query = "DELETE FROM CurrentWeather WHERE NOT `Updated_at` = CURRENT_DATE();";
  #Selecting the data from the database with the following conditions.
  $selector = "SELECT * FROM CurrentWeather WHERE name = '{$city}, {$countryCode}';"; 

  #Running the query using mysqli_query method.
  mysqli_query($conn, $del_query); 
  $extractedData = mysqli_query($conn, $selector); 
  
  $numRows = mysqli_num_rows($extractedData); #Calculating the number of rows.

    $data = array(); #Creating an empty array to store the data into the data variable.
    if ($numRows > 0) { #When number of rows exists.
        while ($row = mysqli_fetch_assoc($extractedData)) { #Using while loop. 
            $data[] = $row; #Appending the data from the Database inside $row variable to $data array.
        }
    } 

    #Making this conditions in order to meet the criteria. This helps us to know where the data is coming from.
    if ($noOfRow > 0){ #When the data exists in the database.
      $fromDatabase = 'Data extracted from the Database.';
      $data[] = $fromDatabase;
    }
    else{ #When it does not.
      $fromInternet = 'Data extracted from the Internet';
      $data[] = $fromInternet;
    }

    $json = json_encode($data); #Convert the array to JSON
    
    echo($json); #Echo helps the JS file to fetch the data by catching the echo'ed content..
  }


#This call will be made after the data is present in the Database.
currentData($cityname, $cc, $numRowsOne, $conn);

?>



 

 