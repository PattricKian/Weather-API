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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
            crossorigin=""></script>
    <title>Visitors</title>
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
<div class="container">
    <div class="row">
        <div class="col">
            <table class="table table-hover" id="flagTable"></table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-hover" id="timeTable">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Local time of visits</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody id="timeBody"></tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p id = "head_text">Map of visits</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="countryModal" tabindex="-1" aria-labelledby="countryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" id="countryModalLabel"></p>
            </div>
            <div class="modal-body">
                <table class="table table-hover" id="countryTable">
                    <thead>
                        <tr>
                            <th id="locationHead"></th>
                            <th id="visitorsHead"></th>
                        </tr>
                    </thead>
                    <tbody id="countryBody"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>



    let oReq = new XMLHttpRequest();
    oReq.onload = function () {
        let data = JSON.parse(this.responseText)
        let visits = data[0]
        let locations = data[1]
        const countryModal = new bootstrap.Modal(document.getElementById('countryModal'), {
            keyboard: false
        });
        const flagTable = document.querySelector("#flagTable")

        const flagTableHead = document.createElement('thead')
        let flagTableTr1 = document.createElement('tr')
        let flagTableThF = document.createElement('th')
        flagTableThF.innerHTML = "Flag"
        flagTableTr1.appendChild(flagTableThF)
        let flagTableThN = document.createElement('th')
        flagTableThN.innerHTML = "Country"
        flagTableTr1.appendChild(flagTableThN)
        let flagTableThC = document.createElement('th')
        flagTableThC.innerHTML = "Visitors count"
        flagTableTr1.appendChild(flagTableThC)
        flagTableHead.appendChild(flagTableTr1)

        const flagTableBody = document.createElement('tbody')
        Object.entries(visits).forEach(( entry ) => {
            const [key, value] = entry;
            let country = key;
            let code = "";

            fetch('https://restcountries.com/v3.1/name/' + country)
            .then(response => response.json())
  
            .then(data => {
             code = data[0].cca2;
            console.log(code);
            })
            .catch(error => console.error(error))
  .finally(() => {
   
    const image = document.createElement('img')
            image.src = "https://flagsapi.com/"+code+"/shiny/64.png";
            image.setAttribute("alt", "fail")
            image.setAttribute("class", "flagImage")
            flagTableFlag.appendChild(image)

  });

            
            
            
         

           

         
            let flagTableTr = document.createElement('tr')
            let flagTableFlag = document.createElement('td')
            flagTableFlag.style.width = "40%"
            flagTableTr.appendChild(flagTableFlag)
            let flagTableCountry = document.createElement('td')
            flagTableCountry.innerHTML = key
            flagTableTr.appendChild(flagTableCountry)
            let flagTableCount = document.createElement('td')
            flagTableCount.innerHTML = value.toString()
            flagTableTr.appendChild(flagTableCount)
            flagTableTr.style.cursor = "pointer";
            flagTableTr.addEventListener("click", () => {
                document.querySelector("#countryBody").innerHTML = ""
                document.querySelector("#countryModalLabel").innerHTML = key
                document.querySelector("#locationHead").innerHTML = "Location"
                document.querySelector("#visitorsHead").innerHTML = "Visitors count"
                let tableData = []
                locations.forEach(place => {
                    if(place.country === key) {
                        if(tableData[place.location]) {
                            tableData[place.location]++;
                        } else {
                            tableData[place.location] = 1
                        }
                    }
                });
                Object.entries(tableData).forEach(( entry ) => {
                    const [key, value] = entry;
                    let countryTableTr = document.createElement('tr')
                    let countryTableLoc = document.createElement('td')
                    countryTableLoc.innerHTML = key
                    countryTableTr.appendChild(countryTableLoc)
                    let countryTableCnt = document.createElement('td')
                    countryTableCnt.innerHTML = value
                    countryTableTr.appendChild(countryTableCnt)
                    document.querySelector("#countryBody").appendChild(countryTableTr)
                });
                countryModal.show()
            })

            flagTableBody.appendChild(flagTableTr)
        });
        flagTable.appendChild(flagTableHead)
        flagTable.appendChild(flagTableBody)

        
        
        
        
        
        
        const timeTableBody = document.querySelector("#timeBody")
        const fixedTimes1 = ['6:00-15:00', '15:00-21:00', '21:00-24:00', '24::00', '14:00', '15:00', '16:00']

        let count = 1;
let timeData = [];
let visitCounts = {}; 

locations.forEach(place => {
  if (timeData[place.location]) {
    timeData[place.location].push(place.local_time);
  } else {
    timeData[place.location] = [];
    timeData[place.location].push(place.local_time);
  }
  
  // prepočítajte počet návštev pre každú lokalitu
  visitCounts[place.location] = (visitCounts[place.location] || 0) + 1;
});

Object.entries(timeData).forEach(entry => {
  const [key, value] = entry;
  let timeTableTr = document.createElement('tr');
  let timeTableLoc = document.createElement('td');
  let timeTableCount = document.createElement('td');
  let timeTableTime = document.createElement('td');
  
  timeTableLoc.innerHTML = key;
  timeTableTr.appendChild(timeTableLoc);

  value.forEach(timestamp => {
    timestamp = timestamp.slice(0, -3);
    if (timeTableTime.innerHTML !== "") {
      timeTableTime.innerHTML = timeTableTime.innerHTML + "<br>" + timestamp;
    } else {
      timeTableTime.innerHTML = timestamp;
    }
  });
  timeTableTr.style.borderColor = "lightgrey";
  timeTableTr.appendChild(timeTableTime);
  
  timeTableCount.innerHTML = visitCounts[key]; 
  timeTableTr.appendChild(timeTableCount);
  
  timeTableBody.appendChild(timeTableTr);
});

        
        
        
        
        
        
        let map = L.map('map').setView([39.3, -3.9], 2);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoicGF0dHJpY2tvZmYiLCJhIjoiY2xoM2RldjFrMW1vZzNkb2d2c2JuMHVpdSJ9.W-HEIcwUshp0wVHwUL1tDA'
        }).addTo(map);
        locations.forEach(loc => {
            L.marker([loc.lat, loc.lng]).addTo(map);
        });
    };
    oReq.open("get", "locationAPI.php", true);
    oReq.send();

</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>