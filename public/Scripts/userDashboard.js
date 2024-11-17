let accesstoken;
let driverId;
let dumpsiteLoc;
let waypointMarker = [];
let getDriverData = new Promise(async (resolve, reject) => {
    try {
        const token = localStorage.getItem('access_token');

        const response = await fetch(`/api/get/auth/info?token=${token}&type=driver`, {
            method: 'GET',
            headers: { "Content-Type": "application/json" }
        });

        const result = response.json();

        resolve(result);
    } catch (error) {
        reject(error);
    }
});

async function getDriverDataInfo() {
    return await getDriverData;
}

window.onload = async () => {
    const token = localStorage.getItem('access_token');
    accesstoken = token;


    const userInfo = await getDriverDataInfo();
    driverId = userInfo.data.td_id;
    loadZoneInfo();
    loadMap();

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
let enterZoneTimeStamp = [];
let brgy_id;
let dumpsiteEnterStatus = false;
let zoneCompleteStatus = false;
let nearestWaypointId;
async function loadMap() {
    $.ajax({
        type: "GET",
        url: `/api/get/zone/getdriverassignedzone?driver_id=${driverId}`,
        dataType: "json",
        success: res => {
            
            if(!res.result.data[3]){
                isShow('notScheduleToday', true);
                isShow('todaySchedule', false, 'block');
                return
            }

            const assignedZone = [];

            const data = res.result.data[1];
            data.forEach(feat => {
                const geodata = {};

                geodata.type = feat.type;
                const geometry = {};

                geometry.type = feat.geometry_type;
                const coordinates = [];
                feat.coordinates.forEach(coo => {
                    coordinates.push([parseFloat(coo.gd_longitude), parseFloat(coo.gd_latitude)]);
                });
                geometry.coordinates = [[coordinates]];

                geodata.geometry = geometry;

                const properties = {};
                properties.name = feat.brgy_name;
                properties.color = res.result.data[0].zone_color;
                properties.brgy_id = feat.brgy_id;
                geodata.properties = properties;
                assignedZone.push(geodata);
            });


            map = new mapboxgl.Map({
                container: 'map', // container ID
                style: 'mapbox://styles/mapbox/satellite-streets-v12', // style URL
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
            dumpsiteLoc = dumpsite.settings_value;
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
                    const dumpSiteLocation = dumpsiteLoc.split(',');

                    const dumpSiteLong = parseFloat(dumpSiteLocation[0]);
                    const dumpSiteLat = parseFloat(dumpSiteLocation[1]);
                    
                    const currentLocationPoint = turf.point([e.coords.longitude, e.coords.latitude]);
                    const dumpSitePoint = turf.point([dumpSiteLong, dumpSiteLat]);

                    const placeName = await getPlaceName(e.coords.longitude, e.coords.latitude);

                    setText('currentLocationName', placeName);

                    const waypointProximityThreshold = 10; // 10 meters threshold
                    let stayTimer = null;
                    let countdownTimer = null;
                    let countdownValue = 180; // 3 minutes in seconds (180 seconds)
                    waypointMarker.forEach((waypoint) => {
                        const waypointCoords = waypoint.marker.getLngLat();
                        const waypointPoint = turf.point([waypointCoords.lng, waypointCoords.lat]);
                        const distanceToWaypoint = turf.distance(currentLocationPoint, waypointPoint, { units: 'meters' });

                        if (distanceToWaypoint <= waypointProximityThreshold) {
                            if (!nearestWaypointId || nearestWaypointId !== waypoint.id) {
                                nearestWaypointId = waypoint.id;

                                // Clear any existing timers
                                if (stayTimer) clearTimeout(stayTimer);
                                if (countdownTimer) clearInterval(countdownTimer);

                                // Reset the countdown value and start updating it
                                countdownValue = 120; // 2 minutes in seconds
                                updateCountdownText(countdownValue); // Initial countdown display

                                // Start the countdown interval
                                countdownTimer = setInterval(() => {
                                    countdownValue--;
                                    updateCountdownText(countdownValue);

                                    if (countdownValue <= 0) {
                                        clearInterval(countdownTimer);
                                    }
                                }, 1000); // Update every second

                                stayTimer = setTimeout(() => {
                                    // Execute the function only if the driver stays for 3 minutes
                                    reactive('cpmpleteCollectionBtn', false);
                                    clearInterval(countdownTimer); // Stop the countdown timer when the time is up
                                    updateCountdownText(0); // Set to 0 after the timer completes
                                    setText('cpmpleteCollectionBtn', 'Complete Collection in this waypoint');
                                }, 1000 * 60 * 2); 
                            }
                        } else {
                            // If the driver moves away from the waypoint, clear the timer and reset the button
                            if (nearestWaypointId === waypoint.id) {
                                nearestWaypointId = null;

                                if (stayTimer) clearTimeout(stayTimer);
                                if (countdownTimer) clearInterval(countdownTimer);

                                reactive('cpmpleteCollectionBtn', true);
                                resetCountdownText(); // Reset button text when the driver moves away
                            }
                        }
                    });

                    const csrf = await getCSRF();

                    $.ajax({
                        type: "POST",
                        url: "/api/post/drivers/updatelocation",
                        data: { "_token": csrf, driver_id: driverId, longitude: e.coords.longitude, latitude: e.coords.latitude },
                        success: res => {
                            //
                        }, error: xhr => console.log(xhr.responseText)
                    });


                    const distanceToDumpSite = turf.distance(currentLocationPoint, dumpSitePoint, { units: 'meters' });
                    const proximityThreshold = 100; // Set your threshold distance (in meters)
                    if (distanceToDumpSite <= proximityThreshold) {
                        isShow('turnOverToDumpsite', true, 'block');
                        isShow('cpmpleteCollectionBtn', false, 'block');
                        setText('turnOverToDumpsite', "Dumpsite Turn Over(Nearby! Get closer to turn over)")
                        if (distanceToDumpSite < 20) {
                            setText('turnOverToDumpsite', "Dumpsite Turn Over")
                            if (!dumpsiteEnterStatus) {
                                reactive('turnOverToDumpsite', false);
                            }
                        }
                    } else {
                        isShow('turnOverToDumpsite', false, 'block');
                        isShow('cpmpleteCollectionBtn', true, 'block');
                        reactive('turnOverToDumpsite', true);
                        dumpsiteEnterStatus = false;
                    }
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

        }, error: xhr => console.log(xhr.responseText)
    });




}


function addWaypoint(coordinates, id) {
    const marker = new mapboxgl.Marker()
        .setLngLat(coordinates)
        .addTo(map);
    waypointMarker.push({ marker, id });
}

// Function to update the button text with the countdown
function updateCountdownText(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    const formattedTime = `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    setText('cpmpleteCollectionBtn', `Stay for: ${formattedTime}`);
}

// Function to reset the button text when the countdown is not active
function resetCountdownText() {
    setText('cpmpleteCollectionBtn', 'Complete Collection');
}


function loadZoneInfo() {
    const header = document.getElementById('infoAssignedZone');
    const table = document.getElementById('infoTable');
    const tableBody = document.getElementById('infoTableBody');
    waypointMarker.length = 0;
    $.ajax({
        type: "GET",
        url: `/api/get/drivers/getzoneinfo?driverid=${driverId}`,
        dataType: "json",
        success: async res => {
            const data = res.result.data;
            const existWaypointsReq = await $.ajax({
                type: "GET",
                url: `/api/get/zone/getallwaypoint?zone=${data[0].zone_id}&type=driver&id=${driverId}`,
                dataType: "json"
            });


            const waypointsData = await existWaypointsReq;

            waypointsData.result.data.forEach(d => {
                addWaypoint([parseFloat(d.longitude), parseFloat(d.latitude)], d.wp_id);
            });

            header.textContent = data[0].zone_name;

            let rows = ''

            tableBody.innerHTML = '';

            const processRows = (context, value) => {
                rows += `<tr><td>${context}</td><td>${value}</tr>`;
            }

            const bullitized = (array, attrib, single = true) => {
                let bullet = '';

                if(!array){
                    bullet += `<li>NO DATA AVAILABLE</li>`;
                    return `<ul>${bullet}</ul>`;
                }

                if (single) {
                    array.forEach(data => {
                        bullet += `<li>${data[attrib]}</li>`
                    });

                } else {
                    attrib.forEach(att => {
                        bullet += `<li>${att == 'can_carry' ? 'CAPACITY' : att.toUpperCase()}: ${array[att] == 'can_carry' ? array[att] + 'Tons' : array[att]}</li>`
                    })
                }
                return `<ul>${bullet}</ul>`;
            }
            processRows('Baranggay List', bullitized(data[4], 'brgy_name'));
            processRows('Truck Details', bullitized(data[1], ['model', 'plate_num', 'can_carry'], false));
            processRows('Standby Driver', bullitized(data[2], ['name', 'address', 'contact', 'license'], false));
            processRows('Standby Truck Info', bullitized(data[3], ['model', 'plate_num', 'can_carry'], false));


            tableBody.innerHTML = rows;

        }, error: xhr => {
            console.log(xhr.responseText);
            const data = JSON.parse(xhr.responseText);
            header.textContent = data.message;
            table.classList.add('d-none');
        }
    });
}

async function activateUser() {
    const csrf = await getCSRF();
    $.ajax({
        type: "POST",
        url: "/api/post/drivers/active",
        data: { "_token": csrf, "driver_id": driverId },
        success: res => {
            parseResult(res);
        }, error: xhr => {
            console.log(xhr.responseText);
            parseResult(JSON.parse(xhr.responseText))
        }
    })
}

document.getElementById('cpmpleteCollectionBtn').addEventListener('click', async () => {
    load.on();
    const csrf = await getCSRF();

    $.ajax({
        type: "POST",
        url: "/api/post/drivers/completecollection",
        data: { "_token": csrf, "driver_id": driverId, "waypoint_id": nearestWaypointId },
        success: res => {
            load.off();
            zoneCompleteStatus = true;
            parseResult(res);
            loadMap();
            loadZoneInfo();
            setText('cpmpleteCollectionBtn', "Continue Collection");
            reactive('cpmpleteCollectionBtn', true);
        }, error: xhr => {
            load.off();
            console.log(xhr.responseText)
            parseResult(JSON.parse(xhr.responseText))
        }
    });
});


document.getElementById('turnOverToDumpsite').addEventListener('click', async () => {
    load.on();
    const csrf = await getCSRF();
    $.ajax({
        type: "POST",
        url: "/api/post/drivers/dumpsiteturnover",
        data: { "_token": csrf, "td_id": driverId },
        success: res => {
            parseResult(res);
            load.off();
            reactive('turnOverToDumpsite', true);
            setText('turnOverToDumpsite', "Done! Collect More Garbage");
            dumpsiteEnterStatus = true;
        }, error: xhr => {
            console.log(xhr.responseText);
            load.off();
            parseResult(JSON.parse(xhr.responseText));
        }
    })
});


function loadRecords() {

    $.ajax({
        type: "GET",
        url: `/api/get/drivers/records?driver_id=${driverId}`,
        dataType: "json",
        success: async res => {
            const collection = res.result.data[0];
            const dumpsite = res.result.data[1];
            console.log(collection);
            // Create an array to hold promises for all place names
            const placeNamePromises = collection.map(data => {
                return getPlaceName(data.longitude, data.latitude).then(place_name => ({
                    ...data,
                    place_name // Attach the resolved place_name to the data object
                }));
            });
    
            // Wait for all place names to be fetched
            const enrichedCollection = await Promise.all(placeNamePromises);
    
            if ($.fn.DataTable.isDataTable('#collectionReports')) {
                $('#collectionReports').DataTable().clear().rows.add(enrichedCollection).draw();
            } else {
                $('#collectionReports').DataTable({
                    data: enrichedCollection,
                    columns: [
                        {
                            title: "Waypoint Location",
                            data: "place_name" // Use the pre-fetched place_name directly
                        },
                        {
                            title: "Time Completed",
                            data: null,
                            render: data => {
                                const date = new Date(data.created_at.replace(' ', 'T'));
            
                                // Extract the hours and minutes
                                let hours = date.getHours();
                                const minutes = date.getMinutes();
            
                                // Determine AM/PM and adjust hours
                                const ampm = hours >= 12 ? 'PM' : 'AM';
                                hours = hours % 12;
                                hours = hours || 12; // Adjust hour '0' to '12' for AM/PM
            
                                // Format minutes to be two digits
                                const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
            
                                // Combine hours, minutes, and AM/PM into the desired format
                                return `${hours}:${formattedMinutes} ${ampm}`;
                            }
                        },
                        {
                            title: "Date",
                            data: null,
                            render: data => {
                                const date = new Date(data.created_at);
            
                                // Array of month names
                                const months = [
                                    "January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"
                                ];
            
                                // Extract the month, day, and year
                                const month = months[date.getMonth()]; // getMonth() returns month index (0-11)
                                const day = date.getDate();
                                const year = date.getFullYear();
            
                                // Combine into the desired format
                                return `${month}, ${day}, ${year}`;
                            }
                        }
                    ]
                });
            }
    
            const cleanData = groupByMonthYear(dumpsite);
    
            if ($.fn.DataTable.isDataTable('#dumpsiteTurnOverRecords')) {
                $('#dumpsiteTurnOverRecords').DataTable().clear().rows.add(cleanData).draw();
            } else {
                $('#dumpsiteTurnOverRecords').DataTable({
                    data: cleanData,
                    columns: [
                        { title: "Month-Year", data: "month" },
                        { title: "Total Turn Over(Tons)", data: "total_turnover" }
                    ]
                });
            }
        }, 
        error: xhr => console.log(xhr.responseText)
    });
    
}

function groupByMonthYear(data) {
    // Create a map to hold the totals of dt_id for each month-year
    const monthYearMap = new Map();

    data.forEach(item => {
        // Extract the month and year from the created_at date
        const date = new Date(item.created_at);
        const monthYear = `${date.toLocaleString('default', { month: 'long' })}-${date.getFullYear()}`;

        // Initialize the entry if it doesn't exist
        if (!monthYearMap.has(monthYear)) {
            monthYearMap.set(monthYear, { month: monthYear, total_turnover: 0 });
        }

        // Add the dt_id to the corresponding month-year total
        monthYearMap.get(monthYear).total_turnover += parseInt(item.capacity);
    });

    // Convert the map to an array of objects
    const result = Array.from(monthYearMap.values());

    return result;
}

async function getschedule(){
    const response = await $.ajax({
        type:"GET",
        url: "/api/get/drivers/loadschedules",
        dataType: "json"
    });

    const result = response.result.data;

    return result
}

async function loadschedules(){

    const result = await getschedule();
    
    loadAllSchedule(result);
}

async function loadAllSchedule(data) {
    const table = document.getElementById('allSchedulesTable');
    table.innerHTML = '';
    let tr = '';

    if (data.days === 'everyday') {
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        for (const d of days) {
            let waypoints = '';

            if (data.zone_sched.length > 0) {
                const waypointPromises = data.zone_sched
                    .filter(wp => wp.days === d)
                    .map(async wp => {
                        const placeName = await getPlaceName(wp.latitude, wp.longitude);
                        return `${wp.days} => ${placeName}`;
                    });

                // Wait for all waypointPromises to resolve
                const resolvedWaypoints = await Promise.all(waypointPromises);
                waypoints = resolvedWaypoints.join(', ');
            }else{
                waypoints = 'Not Set';
            }

            tr += `<tr>
                <td>${d}</td>
                <td>${convertToAmPm(data.collection_start)} - ${convertToAmPm(data.collection_end)}</td>
                <td>${waypoints}</td>
            </tr>`;
        }

        table.innerHTML = tr;
    }else{
        const days = data.days.split(',');
        for (const d of days) {
            let waypoints = '';

            if (data.zone_sched.length > 0) {
                const waypointPromises = data.zone_sched
                    .filter(wp => wp.days === d)
                    .map(async wp => {
                        const placeName = await getPlaceName(wp.latitude, wp.longitude);
                        return `${wp.days} => ${placeName}`;
                    });

                // Wait for all waypointPromises to resolve
                const resolvedWaypoints = await Promise.all(waypointPromises);
                waypoints = resolvedWaypoints.join(', ');
            }else{
                waypoints = 'Not Set';
            }

            tr += `<tr>
                <td>${d}</td>
                <td>${convertToAmPm(data.collection_start)} - ${convertToAmPm(data.collection_end)}</td>
                <td>${waypoints}</td>
            </tr>`;
        }

        table.innerHTML = tr;
    }
}

async function loadTodaySchedule(data){
    const table = document.getElementById('allSchedulesTable');
    table.innerHTML = '';
    let tr = '';
    const currentDate = new Date();
    const currentDay = currentDate.toLocaleString('en-US', { weekday: 'short' });

    if (data.days === 'everyday') {
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        for (const d of days) {
            let waypoints = '';

            if (data.zone_sched.length > 0) {
                const waypointPromises = data.zone_sched
                    .filter(wp => wp.days === d)
                    .map(async wp => {
                        const placeName = await getPlaceName(wp.latitude, wp.longitude);
                        return `${wp.days} => ${placeName}`;
                    });

                // Wait for all waypointPromises to resolve
                const resolvedWaypoints = await Promise.all(waypointPromises);
                waypoints = resolvedWaypoints.join(', ');
            }else{
                waypoints = 'Not Set';
            }
            if(d == currentDay){
                tr += `<tr>
                <td>${d}</td>
                <td>${convertToAmPm(data.collection_start)} - ${convertToAmPm(data.collection_end)}</td>
                <td>${waypoints}</td>
                </tr>`;
            }

      
        }

        table.innerHTML = tr;
    }else{
        const days = data.days.split(',');
        for (const d of days) {
            let waypoints = '';

            if (data.zone_sched.length > 0) {
                const waypointPromises = data.zone_sched
                    .filter(wp => wp.days === d)
                    .map(async wp => {
                        const placeName = await getPlaceName(wp.latitude, wp.longitude);
                        return `${wp.days} => ${placeName}`;
                    });

                // Wait for all waypointPromises to resolve
                const resolvedWaypoints = await Promise.all(waypointPromises);
                waypoints = resolvedWaypoints.join(', ');
            }else{
                waypoints = 'Not Set';
            }

           if(d == currentDay){
                tr += `<tr>
                <td>${d}</td>
                <td>${convertToAmPm(data.collection_start)} - ${convertToAmPm(data.collection_end)}</td>
                <td>${waypoints}</td>
            </tr>`;
            }
        }

        table.innerHTML = tr;
    }
}

document.getElementById('scheduleFilter').addEventListener('change',async e=> {
    const type = e.target.value;
    const result = await getschedule();
    if(type== 'all'){
        loadAllSchedule(result);
    }else{
        loadTodaySchedule(result);
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