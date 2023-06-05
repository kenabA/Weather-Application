//Selecting all the necessary classes.
const header = document.querySelector("header");
const searchIcon = document.querySelector(".search-logo");
const inputSearch = document.querySelector(".input-search");
const body = document.querySelector("body");
const mainTempBG = document.querySelector(".temperature");
const vm = document.querySelector(".view-more");
const load = document.querySelector(".loader");
const blur = document.querySelector(".all-content");
const temp = document.querySelector(".main-temperature");
const desc = document.querySelector(".desc");
const name = document.querySelector(".city-name");
const pressure = document.querySelector(".pres");
const windSpd = document.querySelector(".wind-spd");
const humidity = document.querySelector(".humdt");
const dateTime = document.querySelector(".date-time");
const weatherIcon = document.querySelector(".icon-weather");

//Creating a current date variable.
let dateNow = new Date().toISOString().split('T')[0];
console.log(dateNow)
function main(city) {
  //This will set the city name to lowercase. Now we can access offline data even when the city's name is 'Kathmandu' or 'kAtHmAnDu' or 'kathmandu'.
  lowCity = `${city}`.toLowerCase()

  //Gets the data of the desired item.
  get = localStorage.getItem(lowCity);

  //If the data is present in the Local Storage and matches todays date.
  if(get){
    //JSON.parse converts the JSON String into JavaScript objects.
    jsObj = JSON.parse(get);
    //When the date and time of the data matches with the current time.
    if (jsObj[0]['Date_Time'] == dateNow) {
        //Calling another function of local storage.
        showLocalContent(jsObj);
    } else {
        //Else, calling the function to fetch data.
        fetchData(city);
    }
  }
  else {
      fetchData(city);
      }
} 

function fetchData(city) {
    //Fetching from the PHP File (current_data). The 'city' parameter will help us attach the name of the city in the url so that we can search for it in the PHP File.
    fetch(
      `Kenab_Kushal_K.C._2332336_current_data.php?cityName=${city}`
    )
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        //Removes blur from the user interface.
        blur.classList.remove("blur");

        //Closing the loader when the data fetches.
        load.style.display = "none";


        //Logging into the console as per the question.
        console.log(data[1]);

        //Formating the date-time to YYYY-MM-DD from DD-MM-YYYY
        data[0].Date_Time = dateNow;

        // ---------- Storing in the Local Storage ---------- //

        const db = JSON.stringify(data);
        localStorage.setItem(lowCity, db);
        
        //Replacing the datas.
        temp.innerHTML = `${data[0].Temperature}°C`;
        desc.innerHTML = `${data[0].Description}`;
        name.innerHTML = `${data[0].Name}`;
        pressure.innerHTML = `${data[0].Pressure} hPa`;
        windSpd.innerHTML = `${data[0].Windspeed} m/s`;
        humidity.innerHTML = `${data[0].Humidity}%`;
        dateTime.innerHTML = `${data[0].Date_Time}`;

        //Storing the icon's code into the icon variable.
        const icon = data[0].Icon;

        //Updating the source of the icon class to the icon's value.
        weatherIcon.src = `https://openweathermap.org/img/w/${icon}.png`;

        temp.style.color = "#194000";
        body.style.backgroundColor = "#d7e9cc";
        mainTempBG.style.background = "linear-gradient(#74bf44, #459a0f)";
      })
      .catch((error) => {
        //Removes blur from the user interface.
        blur.classList.remove("blur");

        //In case of error, following code below will execute:
        load.style.display = "none";
        console.log(error);
        temp.innerHTML = "";
        desc.innerHTML = "";
        name.innerHTML = "";
        pressure.innerHTML = "";
        windSpd.innerHTML = "";
        humidity.innerHTML = "";
        dateTime.innerHTML = "";
        temp.style.color = "white";
        temp.innerHTML = ":(";
        name.innerHTML = "An error occured.";
        desc.innerHTML = "Could not find the city";
        weatherIcon.src =
          "https://cdn-icons-png.flaticon.com/512/340/340546.png?w=1800&t=st=1680201252~exp=1680201852~hmac=ef540b644bf03a3260d39e1735106bfc326fa1a3357f3fd486090ab9ded135a3";
        body.style.backgroundColor = "#E9A8A9";
        mainTempBG.style.background = "linear-gradient(#B41C1C, #6C1015)";
      });
}


//Calling it first as it is the default city of ours.
main("Peoria");

//This code below helps us save the key value into the local storage. So that we can utilise it later.
localStorage["currentCity"] = "Peoria";

//This code will run everytime the user clicks the search button or if the user wants to search a city.
function changeCity() {
   if (inputSearch.style.display === 'none') {
   inputSearch.style.display = 'block';
  }
  else{
  //Showing the loader when the data is processing.
  blur.classList.add("blur");
  load.style.display = "block";
  const cityName = inputSearch.value;
  localStorage["currentCity"] = cityName;
  console.log(cityName);
  main(cityName);
}
}

//For responsive search bar.
searchIcon.addEventListener("click", function () {
  header.classList.toggle("active"); //When the .active class is inactive, it will be active when clicked and vice versa.
});

//For hitting enter button when user wants to search a city.
inputSearch.addEventListener("keydown", function (e) {
  //When the enter is pressed, the function runs and searches for the city. Here, 'e' represents the key which was entered.
  if (e.keyCode == 13) {
    changeCity();
  }
});

//When the data is present in the Local Storage.
function showLocalContent(city) {
  //Removes blur from the user interface.
  blur.classList.remove("blur");
  //Closing the loader when the data fetches.
  load.style.display = "none";
  //Prints in the console where the data is coming from.
  console.log("Data extracted from the local storage.");
  temp.innerHTML = `${city[0].Temperature}°C`;
  desc.innerHTML = `${city[0].Description}`;
  name.innerHTML = `${city[0].Name}`;
  pressure.innerHTML = `${city[0].Pressure} hPa`;
  windSpd.innerHTML = `${city[0].Windspeed} km/hr`;
  humidity.innerHTML = `${city[0].Humidity}%`;
  dateTime.innerHTML = `${city[0].Date_Time}`;
  //Storing the icon's code into the icon variable.
  const icon = city[0].Icon;
  //Updating the source of the icon class to the icon's value.
  weatherIcon.src = `https://openweathermap.org/img/w/${icon}.png`;

  temp.style.color = "#194000";
  body.style.backgroundColor = "#d7e9cc";
  mainTempBG.style.background = "linear-gradient(#74bf44, #459a0f)";
}
