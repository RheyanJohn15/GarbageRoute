document.getElementById('contact').addEventListener('submit', (e) => {
    e.preventDefault();

    load.on();
    const formData = new FormData($('form#contact')[0]);
    $.ajax({
        type: "POST",
        url: "/api/post/complaints/submit",
        data: formData,
        processData: false,
        contentType: false,
        success: res => {
            load.off();
            const form = document.getElementById('contact');
            const inputs = form.querySelectorAll('input, textarea, select');
            toastr["success"](res.result.response)
            inputs.forEach(input => input.value = '');
            document.getElementById('preview').src = '';
        }, error: xhr =>{
            console.log(xhr.responseText)
            load.off();
            toastr['error'](JSON.parse(xhr.responseText).message);
        }
    });
});

mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
let webmaps;
let marker;
let circleLayerId = 'marker-radius'; 
let driverMarker = [];

window.onload = () =>{

    $.ajax({
        type: "GET",
        url: "/api/get/complaints/getzone",
        dataType: "json",
        success: res=> {
            const zones = document.getElementById('zonelist');
            zones.disabled = false;
            const data = res.result.data;
            zones.innerHTML = ` <option value="" disabled selected> Select Zone of Complaint
                                                    </option>`;

            data.forEach( d => {
                zones.innerHTML += `<option value="${d.zone_id}">${d.zone_name}</option>`
            });
        }, error: xhr=> console.log(xhr.responseText)
    });


    $.ajax({
        type: "GET",
        url: "/api/get/landing/dashboard",
        dataType: "json",
        success: res=> {
            const data = res.result.data;
            
           setText('pendingCounter', data[0]);
           setText('progressCounter', data[1]);
           setText('resolveCounter', data[2]);
           isShow('compLoader', false);
           if(data[3].length == 0){
            isShow('emptyCpl', true);
            return;
           }
           
           isShow('complaintList', true, 'block');
           const compList = document.getElementById('complaintList');

           compList.innerHTML = '';
           let listNum = 1;
           data[3].forEach(d=> {
            compList.innerHTML += `<div class="item">
                            <h4>Complaint #${listNum}</h4>
                            <img src="ComplaintAssets/${d.comp_image}" alt="Complaint Image" class="img-fluid"
                                style="width: 350px; height: 250px; ">
                            <p class="text-start m-0">
                                <strong>Nature:</strong>${d.comp_nature}<br>
                                <strong>Status:</strong> ${checkStatus(d.comp_status)}<br>
                            </p>
                        </div>`;

                        listNum++;
           });
           webmaps = new mapboxgl.Map({
            container: 'webMap', 
            style: 'mapbox://styles/mapbox/satellite-streets-v12', 
            center: [123.0585, 10.8039], 
            zoom: 11, 
            });
        
           const dumpsite = data[5].settings_value.split(',');
           let geoData = [];

           data[4].forEach(d => {
               let mainData = {};
               let geometry = {};
               mainData.type = d.type;
               geometry.type = d.geometry_type;
       
               let coordinates = [];
               d.coordinates.forEach(c => {
                   coordinates.push([parseFloat(c.gd_longitude), parseFloat(c.gd_latitude)]);
               });
       
               let properties = {};
       
               properties.name = d.brgy_name;
               properties.max_waypoint = d.max_waypoint;
               properties.brgy_id = d.brgy_id;
       
               if (d.zone) {
                   properties.color = d.zone.zone_color;
                   properties.zone = d.zone.zone_id;
               } else {
                   properties.color = d.property_color;
               }
       
       
               geometry.coordinates = [[coordinates]];
               mainData.geometry = geometry;
               mainData.properties = properties;
       
               geoData.push(mainData);
           });

           const waypoints = data[6];

           const zones = {
            type: 'FeatureCollection',
            features: geoData,
            };
           webmaps.on('load', () => {
            // Add zones
            webmaps.addSource('zones', {
                type: 'geojson',
                data: zones,
            });
    
            webmaps.addLayer({
                id: 'zones',
                type: 'fill',
                source: 'zones',
                layout: {},
                paint: {
                    'fill-color': ['get', 'color'],
                    'fill-opacity': 0.5,
                },
            });
    
            //Add the initial marker for the dumpsite
            const markerEl = document.createElement('div');
            markerEl.className = 'custom-marker';
            markerEl.style.backgroundImage = 'url("/assets/img/dump.png")'; // Replace with actual URL
            markerEl.style.width = '50px';
            markerEl.style.height = '50px';
            markerEl.style.backgroundSize = 'cover';
            markerEl.style.borderRadius = '50%';
    
            const popup = new mapboxgl.Popup({ offset: 25 }).setText('Dumpsite');
    
            marker = new mapboxgl.Marker(markerEl)
                .setLngLat([parseFloat(dumpsite[0]), parseFloat(dumpsite[1])])
                .setPopup(popup)
                .addTo(webmaps);
    
            // Add the circle radius around the marker
            webmaps.addLayer({
                id: circleLayerId,
                type: 'circle',
                source: {
                    type: 'geojson',
                    data: {
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: [parseFloat(dumpsite[0]), parseFloat(dumpsite[1])],
                        },
                    },
                },
                paint: {
                    'circle-radius': 30,
                    'circle-color': '#ff0000',
                    'circle-opacity': 0.3,
                },
            });
    
            waypoints.forEach(waypoint => {
                new mapboxgl.Marker({
                    color: waypoint.color || waypoint.zone_color, 
                })
                .setLngLat([parseFloat(waypoint.longitude), parseFloat(waypoint.latitude)])
                .addTo(webmaps);
            });
        });
    
        }, error: xhr=> console.log(xhr.responseText)
    });


    loadschedules();
    
}


async function loadschedules(){
    const response = await $.ajax({
        type: "GET",
        url: "/api/get/landing/loadschedule",
        dataType: "json"
    });

    const result = response.result.data;
    console.log(result);

    const table = document.getElementById('driverSchedules');
    table.innerHTML= '';
    let tr = '';

    result.forEach(data=> {
        let initialData = '';

        initialData += `<td><strong>${data.zone_name}</strong></td>`
        
       if(data.schedule){
        if(data.schedule.days == 'everyday'){
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            days.forEach(d=> {
                initialData += `
                <td>${d} (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})</td>
                `
            });
        }else{
            const days = data.days.split(',');
            days.forEach(d=> {
                initialData += `
            <td>${d == 'Mon' ? `Monday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            <td>${d == 'Tue' ? `Tuesday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            <td>${d == 'Wed' ? `Wednesday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            <td>${d == 'Thu' ? `Thursday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            <td>${d == 'Fri' ? `Friday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            <td>${d == 'Sat' ? `Saturday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            <td>${d == 'Sun' ? `Sunday (${convertToAmPm(data.schedule.collection_start)} - ${convertToAmPm(data.schedule.collection_end)})` : 'Not Available'}</td>
            `
            });
        }
       }else{
        initialData +=`
        <td>Not Available</td>
        <td>Not Available</td>
        <td>Not Available</td>
        <td>Not Available</td>
        <td>Not Available</td>
        <td>Not Available</td>
        <td>Not Available</td>
        `;
       }

        tr += `<tr>${initialData}</tr>`;
    });

    table.innerHTML = tr;
    
}
function checkStatus(status){
    switch(status){
        case 0:
            return 'Pending';
        case 1:
            return 'In Action';
        default:
            return 'Resolved';
    }
}
window.addEventListener('keydown', function(event) {
    // Check if the 'Shift' key is pressed along with the 'A' key
    if (event.shiftKey && event.key === 'A') {
            window.location.href = "/auth/login";
    }
});


function convertToAmPm(time24) {
    // Split the time into hours and minutes
    const [hour, minute] = time24.split(':').map(Number);
    
    // Determine AM or PM
    const period = hour >= 12 ? 'PM' : 'AM';
    
    // Convert hour to 12-hour format
    const hour12 = hour % 12 || 12;
  
    // Return formatted time with AM/PM
    return `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
  }

  async function getPlaceName(latitude, longitude) {
    // Prepare the Mapbox Geocoding API URL
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${longitude},${latitude}.json?access_token=${mapboxgl.accessToken}`;

    try {
        // Fetch the response from the Mapbox API
        const response = await fetch(url);

        // Check if the response is ok (status code 200-299)
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the JSON response
        const data = await response.json();

        // Check if any features were returned
        if (data.features && data.features.length > 0) {
            // Get the place name from the first feature
            return data.features[0].place_name;
        } else {
            return "No place found for the provided coordinates.";
        }
    } catch (error) {
        // Handle any errors that occurred during the fetch
        console.error("Error fetching place name:", error);
        return "Error fetching place name.";
    }
}


function updateRouteStatus(route) {
    const data = route.data;

    // Remove existing markers from the map
    driverMarker.length = 0;
    driverMarker = [];

    data.forEach(async e => {
        const splitCoord = e.ad_coordinates.split(',');
        const markerEl = document.createElement('div');
        markerEl.className = 'custom-marker';

        console.log(e);
        // Add Font Awesome icon to the custom marker
        markerEl.innerHTML = `
            <div style="text-align: center;">
                <i class="fas fa-truck-moving" style="font-size: 24px; color: #6610f2;"></i>
                <p style="margin: 0; -webkit-text-stroke: 1px black; text-stroke: 1px black;  font-weight: bold;   
                 font-size: 20px;color: #fff; text-align:center">${e.truck.plate_num}<br>${e.truck.model}</p>
            </div>`;
        
        const coord = [parseFloat(splitCoord[0]), parseFloat(splitCoord[1])];

        // Create a new marker and add it to the map
        const marker = new mapboxgl.Marker(markerEl)
            .setLngLat(coord) 
            .addTo(webmaps);

        driverMarker.push(marker);
    });
}