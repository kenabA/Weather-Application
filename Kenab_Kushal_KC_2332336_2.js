blur = document.querySelector(".addclass");
blur.classList.add("blur");
loader = document.querySelector(".loader");

//This first fetch() will help us know whether the data has been extracted from the internet or the database.
fetch(
  `Kenab_Kushal_K.C._2332336_For_Past.php?cityName=${localStorage["currentCity"]}`
);

//Using second fetch() to fetch the extracted data of past week from the 'extract_data.php' file.
fetch(
  "Kenab_Kushal_K.C._2332336_extract_data.php?cityName=" +
    localStorage["currentCity"]
)
  .then(function (response) {
    return response.json();
  })
  .then(function (data) {
    console.log(data)
    console.log(data[6]);
    loader.style.display = "none";
    blur.classList.remove("blur");
    //Using the for loop to select all the classes along with changing the textContent of those classes.
    for (i = 0; i < 6; i++) {
      document.querySelector(".day-name-" + (i + 1)).textContent =
        data[i].Date_Time;
      document.querySelector(
        ".t-" + (i + 1)
      ).textContent = `${data[i].Temperature}°C`;
      document.querySelector(".d-" + (i + 1)).textContent = data[i].Description;
      document.querySelector(
        ".h-" + (i + 1)
      ).textContent = `${data[i].Humidity} %`;
      document.querySelector(
        ".w-" + (i + 1)
      ).textContent = `${data[i].Windspeed} m/s`;
      document.querySelector(
        ".p-" + (i + 1)
      ).textContent = `${data[i].Pressure} hPa`;
      document.querySelector(
        ".i-" + (i + 1)
      ).src = `https://openweathermap.org/img/w/${data[i].Icon}.png`;
    }
  })
  //In case of any error, this code below will execute.
  .catch(function (error) {
    //Using the for loop to select all the classes along with changing the textContent of those classes.
    for (i = 0; i < 6; i++) {
      loader.style.display = "none";
      blur.classList.remove("blur");
      document.querySelector(".day-name-" + (i + 1)).textContent = "No Data";
      document.querySelector(".t-" + (i + 1)).textContent = "No Data";
      document.querySelector(".d-" + (i + 1)).textContent = "No Data";
      document.querySelector(".h-" + (i + 1)).textContent = "No Data";
      document.querySelector(".w-" + (i + 1)).textContent = "No Data";
      document.querySelector(".p-" + (i + 1)).textContent = "No Data";
    }
  });
