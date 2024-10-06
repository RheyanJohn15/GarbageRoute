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
}

mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';

let map;

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
          const currentLocationPoint = turf.point([e.coords.longitude, e.coords.latitude]);
          console.log(currentLocationPoint);
        });
      });

    }, error: xhr=> console.log(xhr.responseText)
  });
}




