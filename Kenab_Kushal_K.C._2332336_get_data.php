<?php

//This PHP File is used to get the pastdata from the API and insert into the Database.

#Creating a function for pastData which gives us the data from the past.
function pastData($city, $countryCode){

  global $conn;

  $appid = '6b16146e9f69b9e6c149f2c1e56fb165'; #My API Key
  #Taking out the time stamp for 6 days before.
  $start = time() - (6 * 86400); 
  
  $url = "https://history.openweathermap.org/data/2.5/history/city?q={$city},{$countryCode}&type=day&start={$start}&cnt=120&appid={$appid}"; #URL of the api.
  
  #Fetching the API using CURL.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);

  #Converting JSON into an Associative Array.
  $data = json_decode($response, true); 

  $responseCod = $data['cod'];
  if ($responseCod == null){
    return;
  }

  #Creating new variables to store the date and data into.
  $previousDate = null; #Setting this variable as null. It does not hold any value yet.
  $newData = array(); #New Array to store the filtered data.

  #Using foreach to iterate over each data form the API.
  foreach ($data['list'] as $weatherData) {

    $currentDate = date('Y-m-d', $weatherData['dt']); #This code stores date to the currentDate variable.
    #This checks wether the current date matches the previous date or no. If the current date is not equal to the previous date, then the code below will run.
    if ($currentDate != $previousDate) { 
      $newData[] = $weatherData; #We store the data from the api contained by $weatherData var to the $newData array, one by one.
      $previousDate = $currentDate; #We now replace the previous date with the current date.
    }
  }
  #Using for loop to iterate over the data of past 6 days. ( $newData Array )
  for ($i = 0; $i < 6; $i++){

    $shortcut = $newData[$i]; #Creating this variable to make things easy.
    $Date = $shortcut['dt']; #Gets the time stamp.
    $Temp = $shortcut['main']['temp']; #Gets the temperature but in Kelvin.
    $Temperature = $Temp - 273.15; #Converting into celcius.
    $Pressure = $shortcut['main']['pressure']; #Gets the pressure value.
    $Windspeed = $shortcut['wind']['speed']; #Gets the wind speed value.
    $Humidity = $shortcut['main']['humidity']; #Gets the humidity value.
    $Icon = $shortcut['weather'][0]['icon']; #Gets the humidity value.
    $Description = $shortcut['weather'][0]['description']; #Gets Description.
    $Datetime = date('Y-m-d', $Date); #Gets Time through the $Date variable as a parameter.
    
    
    #Inserting the datas into the database.
    $sql = "INSERT INTO WeatherData (City, Temperature, Pressure,	Windspeed, Humidity, Icon, Description,Date_Time) VALUES ('$city', '$Temperature', '$Pressure', '$Windspeed', '$Humidity', '$Icon', '$Description','$Datetime')";

    #Running the mysqli_query method to execute the query.
    $result = mysqli_query($conn,$sql);
  }

}
  ?>