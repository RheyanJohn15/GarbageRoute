let accesstoken;
let driverId;

let getDriverData = new Promise(async(resolve, reject)=> {
  try{
    const token = localStorage.getItem('access_token');

    const response = await fetch(`/api/get/auth/info?token=${token}&type=driver`, {
      method: 'GET',
      headers: {"Content-Type": "application/json"}
    });

    const result = response.json();

    resolve(result);
  }catch(error){
    reject(error);
  }
});

async function getDriverDataInfo(){
  return await getDriverData;
}

window.onload = async ()=> {
   const token = localStorage.getItem('access_token');
   accesstoken = token;


   const userInfo = await getDriverDataInfo();
   driverId = userInfo.data.td_id;

   loadMap();
   loadZoneInfo();

   activateUser();
}

mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';

let map;

async function getPlaceName(longitude, latitude) {
    const accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q'; // Replace with your Mapbox access token
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${longitude},${latitude}.json?access_token=${accessToken}`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        // Check if features are available in the response
        if (data.features && data.features.length > 0) {
            return data.features[0].place_name; // Return the name of the first place
        } else {
            return 'Location not found';
        }
    } catch (error) {
        console.error('Error fetching place name:', error);
        return 'Error fetching place name';
    }
}

function loadMap(){
  $.ajax({
    type: "GET",
    url: `/api/get/zone/getdriverassignedzone?driver_id=${driverId}`,
    dataType: "json",
    success: res=> {
      const assignedZone = [];

      const data = res.result.data[1];



      data.forEach( feat => {

          const geodata = {};

          geodata.type = feat.type;
          const geometry = {};

          geometry.type = feat.geometry_type;
          const coordinates = [];
          feat.coordinates.forEach(coo=> {
            coordinates.push([parseFloat(coo.gd_longitude), parseFloat(coo.gd_latitude)]);
          });
          geometry.coordinates = [[coordinates]];

          geodata.geometry = geometry;

          const properties = {};
          properties.name = feat.brgy_name;
          properties.color = res.result.data[0].zone_color;
          geodata.properties = properties;
          assignedZone.push(geodata);
      });

    map = new mapboxgl.Map({
        container: 'map', // container ID
        style: 'mapbox://styles/mapbox/streets-v12', // style URL
        center: [123.0585, 10.8039], // Center set to Silay City, Negros Occidental
        zoom: 11, // Starting zoom
      });

       // GeoJSON zones
      const zones = {
        type: 'FeatureCollection',
        features: assignedZone,
      };

      const dumpsite = res.result.data[2];
      const dumpsiteLocation = dumpsite.settings_value.split(',');

      map.on('load', () => {
        // Add zones
        map.addSource('zones', {
          type: 'geojson',
          data: zones,
        });

        map.addLayer({
          id: 'zones',
          type: 'fill',
          source: 'zones',
          layout: {},
          paint: {
            'fill-color': ['get', 'color'],
            'fill-opacity': 0.5,
          },
        });


        // Add geolocation control after the map is loaded
        const geolocate = new mapboxgl.GeolocateControl({
          positionOptions: {
            enableHighAccuracy: true
          },
          trackUserLocation: true
        });

        map.addControl(geolocate, 'top-right');

        geolocate.on('geolocate', async (e) => {
            isShow('currentLocationDiv', true, 'block');
            const currentLocationPoint = turf.point([e.coords.longitude, e.coords.latitude]);
            console.log(currentLocationPoint);

            setText('currentLocationLong', e.coords.longitude);
            setText('currentLocationLat', e.coords.latitude);

            const placeName = await getPlaceName(e.coords.longitude, e.coords.latitude);

            setText('currentLocationName', placeName);
            const csrf = await getCSRF();
            $.ajax({
                type: "POST",
                url: "/api/post/drivers/updatelocation",
                data: {"_token": csrf, driver_id: driverId, longitude: e.coords.longitude, latitude: e.coords.latitude},
                success: res=> {
                    //
                }, error: xhr=> console.log(xhr.responseText)
            })
        });

        geolocate.on('trackuserlocationend', () => {
            isShow('currentLocationDiv', false, 'block');
          });

           // Add the initial marker for the dumpsite
      const markerEl = document.createElement('div');
      markerEl.className = 'custom-marker';
      markerEl.style.backgroundImage = 'url("/assets/img/dump.png")'; // Replace with actual URL
      markerEl.style.width = '50px';
      markerEl.style.height = '50px';
      markerEl.style.backgroundSize = 'cover';
      markerEl.style.borderRadius = '50%';

      const popup = new mapboxgl.Popup({ offset: 25 }).setText('Dumpsite');

      marker = new mapboxgl.Marker(markerEl)
        .setLngLat([parseFloat(dumpsiteLocation[0]), parseFloat(dumpsiteLocation[1])])
        .setPopup(popup)
        .addTo(map);

      // Add the circle radius around the marker
      map.addLayer({
        id: "dumpsite-location",
        type: 'circle',
        source: {
          type: 'geojson',
          data: {
            type: 'Feature',
            geometry: {
              type: 'Point',
              coordinates: [parseFloat(dumpsiteLocation[0]), parseFloat(dumpsiteLocation[1])],
            },
          },
        },
        paint: {
          'circle-radius': 30,
          'circle-color': '#ff0000',
          'circle-opacity': 0.3,
        },
      });

      });

    }, error: xhr=> console.log(xhr.responseText)
  });
}


function loadZoneInfo(){
  const header = document.getElementById('infoAssignedZone');
  const table = document.getElementById('infoTable');
  const tableBody = document.getElementById('infoTableBody');

  $.ajax({
    type: "GET",
    url: `/api/get/drivers/getzoneinfo?driverid=${driverId}`,
    dataType:"json",
    success: res=> {
      const data = res.result.data;

      header.textContent = data[0].zone_name;

      let rows = ''

      tableBody.innerHTML = '';

      const processRows = (context, value) => {
        rows += `<tr><td>${context}</td><td>${value}</tr>`;
      }

      const bullitized = (array, attrib, single = true) => {
        let bullet = '';
        if(single){
          array.forEach(data=> {
            bullet += `<li>${data[attrib]}</li>`
          });

        }else{
          attrib.forEach(att=> {
             bullet += `<li>${att == 'can_carry' ? 'Capacity': att.toUpperCase()}: ${array[att] == 'can_carry' ? array[att] + 'Tons' : array[att]}</li>`
          })
        }
        return `<ul>${bullet}</ul>`;
      }

      processRows('Baranggay List', bullitized(data[4], 'brgy_name'));
      processRows('Truck Details', bullitized(data[1], ['model', 'plate_num', 'can_carry'], false));
      processRows('Standby Driver', bullitized(data[2], ['name', 'address', 'contact', 'license'], false));
      processRows('Standby Truck Info', bullitized(data[3], ['model', 'plate_num', 'can_carry'], false));


      tableBody.innerHTML = rows;

    }, error: xhr=> {
      console.log(xhr.responseText);
      const data = JSON.parse(xhr.responseText);
      header.textContent = data.message;
      table.classList.add('d-none');
    }
  });
}

async function activateUser(){
    const csrf = await getCSRF();
    $.ajax({
        type: "POST",
        url: "/api/post/drivers/active",
        data: {"_token": csrf, "driver_id": driverId},
        success: res=> {
            parseResult(res);
        }, error: xhr=> {
            console.log(xhr.responseText);
            parseResult(JSON.parse(xhr.responseText))
        }
    })
}

