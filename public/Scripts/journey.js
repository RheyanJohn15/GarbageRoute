mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
let map;
let directions;
let markers = [];
let r_coordinates;
const waypointEntryTimes = {};
const wayPointTimeRecord = {};
map = new mapboxgl.Map({
    container: 'map', // container ID
    style: 'mapbox://styles/mapbox/streets-v12', // style URL
    center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
    zoom: 11, // starting zoom
});
map.on('click', (e) => {
    e.stopPropagation();
});

window.onload = () => {
    loadRoute();
}

const geolocate = new mapboxgl.GeolocateControl({
    positionOptions: {
        enableHighAccuracy: true
    },
    trackUserLocation: true
});

map.addControl(geolocate, 'top-right');

geolocate.on('geolocate', async (e) => {

    const currentLocationPoint = turf.point([e.coords.longitude, e.coords.latitude]);

    // Split the coordinates string into an array
    const coordinatesArray = r_coordinates.split(',');
    coordinatesArray.pop();
    // Check if the current location is within 20 meters of any of the circles
    coordinatesArray.forEach((coordinate, index) => {
        if (index % 2 === 0) {
            const coord = coordinate.split('-');

            const longitude = +coord[0];
            const latitude = +coord[1];
            const circleCenter = turf.point([longitude, latitude]);
            const distance = turf.distance(currentLocationPoint, circleCenter, { units: 'meters' });

            if (distance <= 20) {
                // The current location is within 20 meters of the circle
                console.log(`You are within 20 meters of waypoint ${index / 2 + 1}`);
                const waypointId = index / 2 + 1;
                if (!waypointEntryTimes[waypointId]) {
                    waypointEntryTimes[waypointId] = Date.now(); // store the entry timestamp
                    wayPointTimeRecord[waypointId] = 0;
                }
            }
        }
    });

    Object.keys(waypointEntryTimes).forEach((waypointId) => {
        const entryTime = waypointEntryTimes[waypointId];
        const currentTime = Date.now();
        const stayTime = currentTime - entryTime;
        // Check if you're still within the waypoint
        const currentLocationPoint = turf.point([e.coords.longitude, e.coords.latitude]);
        const coordinatesArray = r_coordinates.split(',');
        coordinatesArray.pop();
        coordinatesArray.forEach((coordinate, index) => {
          if (index % 2 === 0) {
            const coord = coordinate.split('-');
            const longitude = +coord[0];
            const latitude = +coord[1];
            const circleCenter = turf.point([longitude, latitude]);
            const distance = turf.distance(currentLocationPoint, circleCenter, { units: 'meters' });
            if (distance <= 20 && index / 2 + 1 == waypointId) {
              console.log(`You've stayed at waypoint ${waypointId} for ${stayTime} milliseconds`);
              wayPointTimeRecord[waypointId] = stayTime;
            } else if (index / 2 + 1 == waypointId) {
              // You've left the waypoint, remove the entry timestamp
              delete waypointEntryTimes[waypointId];
            }
          }
        });
      });
    

    const coordinates = e.coords.longitude + ',' + e.coords.latitude;
    const csrf = await getCSRF();
    const id = getUrlquery('id');
    const timeForWaypoint = Object.keys(wayPointTimeRecord).map(key => `${key}-${wayPointTimeRecord[key]}`).join();
    console.log(timeForWaypoint);
    $.ajax({
        type: "POST",
        url: "/api/post/drivers/updatelocation",
        data: { "_token": csrf, "coordinates": coordinates, "waypointTime": timeForWaypoint, "routeId": id },
        success: res => {
            console.log(res);
        }, error: xhr => console.log(xhr.responseText)
    });
});
function loadRoute() {
    const id = getUrlquery('id');

    $.ajax({
        type: "GET",
        url: `/api/get/drivers/routedetails?routeid=${id}`,
        dataType: "json",
        success: res => {
            const data = res.result.data;
            const coordinates = data.r_coordinates.split(',');
            coordinates.pop();
            console.log(data.progress);
            if (data.progress != "null") {
                setHtml('startGarbageCollection', "<i class='fa fa-truck-moving'></i> Collecting Garbage(Click to Stop)");
            } else {
                setHtml('startGarbageCollection', ` <span class="btn-label">
                  <i class="fa fa-map"></i>
                </span>
                Start Garbage Collection`);
            }

            directions = new MapboxDirections({
                accessToken: mapboxgl.accessToken,
                unit: 'metric',
                profile: 'mapbox/driving',
                controls: {
                    inputs: false, // Hide input boxes
                    instructions: true,
                },
            });
            r_coordinates = data.r_coordinates;

            map.addControl(directions, 'top-left');

            // Add markers to each waypoint
            coordinates.forEach((coordinate, index) => {
                const [longitude, latitude] = coordinate.split('-');
                const marker = new mapboxgl.Marker()
                    .setLngLat([longitude, latitude])
                    .setPopup(new mapboxgl.Popup().setHTML(`Waypoint ${index + 1}`))
                    .addTo(map);
                markers.push(marker);

                map.addLayer({
                    id: `circle-${index}`,
                    type: 'circle',
                    source: {
                        type: 'geojson',
                        data: {
                            type: 'FeatureCollection',
                            features: [
                                {
                                    type: 'Feature',
                                    geometry: {
                                        type: 'Point',
                                        coordinates: [longitude, latitude]
                                    }
                                }
                            ]
                        }
                    },
                    paint: {
                        'circle-radius': 50, // radius in pixels
                        'circle-color': '#007bff', // color of the circle
                        'circle-opacity': 0.5 // opacity of the circle
                    }
                });

            });


            // Add waypoints to the directions control
            coordinates.forEach((coordinate, index) => {
                const [longitude, latitude] = coordinate.split('-');
                directions.addWaypoint(index, [longitude, latitude]);
            });

            // Get directions between each waypoint
            directions.setOrigin(coordinates[0].split('-'));
            directions.setDestination(coordinates[coordinates.length - 1].split('-'));
        }, error: xhr => console.log(xhr.responseText)
    });
}

document.getElementById('startGarbageCollection').addEventListener('click', async (e) => {
    load.on();
    const id = getUrlquery('id');
    const csrf = await getCSRF();

    $.ajax({
        type: "POST",
        url: "/api/post/drivers/startcollection",
        data: { "_token": csrf, "route_id": id },
        success: res => {
            load.off();
            parseResult(res);
            e.target.innerHTML = '';
            e.target.innerHTML = "<i class='fa fa-truck-moving'></i> Collecting Garbage(Click to Stop)";
        }, error: xhr => {
            console.log(xhr.responseText);
            load.off();
            parseResult(JSON.parse(xhr.responseText))
        }
    })
});

