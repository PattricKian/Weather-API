

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <title>Weather </title>
</head>
<body>

<video autoplay muted loop id="myVideo">
  <source src="earth.mp4" type="video/mp4">
</video>

<div class="hamburger-menu">
    <input id="menu__toggle" type="checkbox" />
    <label class="menu__btn" for="menu__toggle">
      <span></span>
    </label>
    
    
    

    <ul class="menu__box">
      <li><a class="menu__item" href="index.php">Weather</a></li>
			<li><a class="menu__item" href="visitors.php">Visitors</a></li>
            
            
  </div>

  <div class="form__group field">
  
</div>


<div class="container">
    <div class="row">

    <div class="input-group">
    <input type ="input" class="form__field" placeholder="Place"  id='location' required />
    <label for="location" class="form__label">Place</label>
    
    </div>
    <div class="container1">
    <a class="button" id="sendLocation" style="--color:#F8F8FF; color: #8f8f8f">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
     Locate
    </a>
    </div>
    </div>
   
    <div class="row">
        <div class="col">
            <div>
                <pre id="info"></pre>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="weatherWidget" class="widget w-128 cursor-pointer border  rounded flex flex-col justify-center items-center text-center p-6 ">
                <div class="text-md font-bold flex flex-col text-gray-900">
                    <span class="uppercase">Today</span>
                    <span class="uppercase" id="weatherPlace">Nitra</span>
                    <span class="font-normal text-gray-700 text-sm" id="weatherDate">July 29</span>
                </div>
                <div class="w-32 h-32 flex items-center justify-center" id="weatherImage"></div>
                <p class="text-gray-700 mb-2" id="weatherState">Partly cloud</p>
                <div class="text-3xl font-bold text-gray-900 mb-1" id="weatherTmp">32º</div>
            </div>
        </div>
    </div>
    <div class="col">
            <div id="infoWidget" class="widget w-128 cursor-pointer border rounded flex flex-col justify-center items-center text-center p-6 ">
                <div class="text-md font-bold flex flex-col text-gray-900">
                <p class="text-gray-700 mb-2" id="weatherState">INFO CENTER</p>
                    <br>
                    <span class="uppercase">GPS coordinates</span>
                    <span class="uppercase" id="infoPlace">fs</span>
                    <br>
                    <span class="uppercase">State</span>
                    <span class="uppercase" id="statePlace">fs</span>
                    <br>
                    <span class="uppercase">Capital City</span>
                    <span class="uppercase" id="capitalPlace">fs</span>


                    
                </div>
               
                
            </div>
        </div>
</div>

<script>
    $(document).ready(function() {
        const locationInput = document.querySelector("#location")
        const locationIDInput = document.querySelector("#locationID")
        const infoDiv = document.querySelector("#info")
        const weatherWidget = document.querySelector("#weatherWidget")
        const infoWidget = document.querySelector("#infoWidget")

        $("#sendLocation").click(function () {
            if (!isBlank(locationInput.value)) {
                $.ajax({
                    url: 'https://api.mapbox.com/geocoding/v5/mapbox.places/'+locationInput.value+'.json?' +
                        'limit=1&' +
                        'access_token=pk.eyJ1IjoicGF0dHJpY2tvZmYiLCJhIjoiY2xoM2RldjFrMW1vZzNkb2d2c2JuMHVpdSJ9.W-HEIcwUshp0wVHwUL1tDA',
                        success: function(response) {
        console.log(response);
    }
                }).done(function ( data ) {
                    if (data.features.length === 0) {
                        console.log("nonexistent")
                        weatherWidget.style.visibility = "hidden"
                        infoWidget.style.visibility = "hidden"
                        infoDiv.innerHTML = ""
                    } else {
                        console.log(data)
                        if(data.features[0].context) {
                            var ctx = 0
                            while(1) {
                                if(!data.features[0].context[ctx]) {
                                    ctx--;
                                    break;
                                }
                                ctx++;
                            }
                            var country = data.features[0].context[ctx].text
                            var short_code = data.features[0].context[ctx].short_code
                        } else {
                            country = data.features[0].place_name
                            short_code = data.features[0].properties.short_code
                        }
                        $.ajax({
                            url: 'https://api.worldbank.org/v2/country/'+short_code+'?format=json'
                        }).done(function ( capitalData ) {
                            $.ajax({
                                url: 'https://api.weatherapi.com/v1/forecast.json?' +
                                    'key=8579085bed204578b99122602233004' +
                                    '&q='+data.features[0].center[1]+","+data.features[0].center[0]+'&' +
                                    '&days=1&aqi=yes&alerts=yes',
                            }).done(function ( weatherData ) {
                                console.log(weatherData)
                                const weatherPlace = document.querySelector("#weatherPlace")
                                const infoPlace = document.querySelector("#infoPlace")
                                const capitalPlace = document.querySelector("#capitalPlace")
                                const statePlace = document.querySelector("#statePlace")
                                const weatherDate = document.querySelector("#weatherDate")
                                const weatherImage = document.querySelector("#weatherImage")
                                const weatherState = document.querySelector("#weatherState")
                                const weatherTmp = document.querySelector("#weatherTmp")
                                let forecast = weatherData.forecast.forecastday[0]
                                weatherDate.innerHTML = forecast.date
                                infoPlace.innerHTML = data.features[0].center[1] + ':' + data.features[0].center[0];
                                weatherPlace.innerHTML = data.features[0].place_name
                                statePlace.innerHTML = country
                                const imgElement = document.createElement("img");
                                imgElement.src = forecast.day.condition.icon;
                                imgElement.setAttribute("alt", "Weather image");
                                weatherImage.innerHTML = ''
                                weatherImage.appendChild(imgElement);
                                weatherState.innerHTML = forecast.day.condition.text;
                                weatherTmp.innerHTML = forecast.day.maxtemp_c + "º"
                                weatherWidget.style.visibility = "visible"
                                infoWidget.style.visibility = "visible"


                                fetch("https://restcountries.com/v2/name/" + country)
                    .then(response => response.json())
  
                            .then(data => {
                            capital = data[0]["capital"];
                            
                            })
                            .catch(error => console.error(error))
                .finally(() => {

                    capitalPlace.innerHTML = capital
                    

                });


                               
                                $.ajax({
                                    type: 'POST',
                                    url: 'https://site36.webte.fei.stuba.sk/weather/locationAPI.php',
                                    data: '{"location":"'+data.query[0]+'",' +
                                        '"country":"'+country+'",' +
                                        '"latitude":'+data.features[0].center[1]+',' +
                                        '"longitude":'+data.features[0].center[0]+',' +
                                        '"local_time":"'+weatherData.location.localtime+'",' +
                                        '"capital":"'+capitalData[1][0].capitalCity+'"' + '}',
                                    dataType: 'json',
                                    success: function(res) {
                                        console.log(res)
                                       
                                    },
                                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        console.log("Error " + XMLHttpRequest)
                                    }
                                });
                            });
                        });
                    }
                });
            }
        });

        function isBlank( str ) {
            return (!str || /^\s*$/.test(str));
        }
    });
</script>
</body>
</html>