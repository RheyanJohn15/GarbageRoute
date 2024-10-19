mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';

window.onload = LoadMap();

async function getGeoData() {
    const response = await fetch('/api/get/zone/getgeodata');

    const result = await response.json();
    const data = result.result.data;

    let geoData = [];

    data.forEach(d => {
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

    return geoData;
}

async function getDumpSiteLocation() {
    const response = await fetch('/api/get/settings/getval?context=dumpsite_location');

    const result = await response.json();
    const data = result.result.data.settings_value;

    return data;
}

let marker; // Variable to store the marker
let isSelecting = false; // Flag to track if the user is selecting a location
let circleLayerId = 'marker-radius'; // ID for the circle layer
let map;
let selectedBarangay = [];
let waypoints = {}; 
let geoDataSilayGlobal;
let waypointMarker = [];
let selectedZoneWaypoints;

async function LoadMap() {

    const geoDataSilay = await getGeoData();
    const dumpsiteLocation = await getDumpSiteLocation();
    const dumpsiteCoord = dumpsiteLocation.split(',');

    const response = await fetch('/api/get/zone/getwaypointadmin', {
        method: "GET",
        headers: {"Content-Type": "application/json"},
    });

    const result = await response.json();
    const waypoints = result.result.data;
    console.log(waypoints);

    geoDataSilayGlobal = geoDataSilay;

    // Initialize map
    map = new mapboxgl.Map({
        container: 'map', // container ID
        style: 'mapbox://styles/mapbox/streets-v12', // style URL
        center: [123.0585, 10.8039], // Center set to Silay City, Negros Occidental
        zoom: 11, // Starting zoom
    });

    // GeoJSON zones
    const zones = {
        type: 'FeatureCollection',
        features: geoDataSilay,
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
            .setLngLat([parseFloat(dumpsiteCoord[0]), parseFloat(dumpsiteCoord[1])])
            .setPopup(popup)
            .addTo(map);

        // Add the circle radius around the marker
        map.addLayer({
            id: circleLayerId,
            type: 'circle',
            source: {
                type: 'geojson',
                data: {
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: [parseFloat(dumpsiteCoord[0]), parseFloat(dumpsiteCoord[1])],
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
            .addTo(map);
        });
    });



    map.on('click', async (e) => {
        if (isSelecting) {
            const lngLat = e.lngLat; // Get the coordinates of the click

            // Update the marker position
            marker.setLngLat([lngLat.lng, lngLat.lat]);

            // Update the circle layer with new coordinates
            map.getSource(circleLayerId).setData({
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [lngLat.lng, lngLat.lat],
                },
            });

            // Disable the selection mode after the new location is set
            isSelecting = false;
            document.getElementById('changeDumpsiteLocation').innerHTML = '<i class="fas fa-warehouse"></i> Change Dumpsite Location';
            load.on();

            const csrf = await getCSRF();
            const longitude = lngLat.lng;
            const latitude = lngLat.lat;

            $.ajax({
                type: "POST",
                url: "/api/post/zone/changedumpsitelocation",
                data: { "_token": csrf, "context": "dumpsite_location", "longitude": longitude, "latitude": latitude },
                success: res => {
                    parseResult(res);
                    load.off();
                }, error: xhr => {
                    console.log(xhr.responseText);
                    parseResult(JSON.parse(xhr.responseText))
                }
            });
        }

    });

}

document.getElementById('changeDumpsiteLocation').addEventListener('click', e => {
    isSelecting = !isSelecting;

    if (isSelecting) {
        $.notify({ 'message': 'You Can now change the location of the dumpsite', 'icon': 'icon-check' }, {
            type: 'success',
            placement: {
                from: 'top',
                align: 'right',
            },
            time: 1000,
            delay: 2000,
        });

        e.target.innerHTML = `<i class="fa fa-edit"></i> Editing.... Click Again to cancel`

    } else {
        $.notify({ 'message': 'Editing Cancelled', 'icon': 'icon-check' }, {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'right',
            },
            time: 1000,
            delay: 2000,
        });
        e.target.innerHTML = '<i class="fas fa-warehouse"></i> Change Dumpsite Location';
    }


});



let silayBrgyTable;
document.getElementById('updateZonesBtn').addEventListener('click', () => {
    getAllBrgyList();
    loadZones('update');
})

function getAllBrgyList() {
    $.ajax({
        type: 'GET',
        url: "/api/get/brgy/list",
        dataType: "json",
        success: res => {
            loadBrgy(res.result.data);

        }, error: xhr => console.log(xhr.responseText)
    });
}

function loadBrgy(data) {
    if (!$.fn.DataTable.isDataTable('#silayBrgy')) {
        silayBrgyTable = $('#silayBrgy').DataTable({
            data: data,
            pageLength: 5,
            columns: [
                { 'title': "Baranggay", data: "brgy_name" },
                {
                    'title': "Assigned Zone", data: null,
                    render: data => {
                        return data.zone_id == null ? 'Not Set' : data.zone_id
                    }
                }
            ]
        });
    } else {
        silayBrgyTable.clear().rows.add(data).draw();
    }

}

document.getElementById('assignDriverBtn').addEventListener('click', () => {
    loadZones('add');
    loadDriver();
});

function loadZones(type) {
    $.ajax({
        type: 'GET',
        url: "/api/get/zone/list",
        dataType: "json",
        success: res => {
            const zones = document.getElementById('filterByZones');
            const addZones = document.getElementById('selectZones');
            zones.innerHTML = `<option value="all">All Baranggays</option>`;
            addZones.innerHTML = '<option value="" disabled selected>------Select a Zone-------</option>';
            const addWaypoint = document.getElementById('selectZoneAddWaypoint');
            const assignDriverZone = document.getElementById('addDriverZoneList');
            assignDriverZone.innerHTML = '';

            addWaypoint.innerHTML = `<option value="" disabled selected>------Select Zone------</option>`

            res.result.data.forEach(z => {
                if (type == 'update') {
                    zones.innerHTML += `<option value="${z.zone_id}">${z.zone_name}</option>`;
                    addZones.innerHTML += `<option value="${z.zone_id}">${z.zone_name}</option>`;
                } else if (type == "assign") {
                    addWaypoint.innerHTML += `<option value="${z.zone_id}-${z.zone_name}">${z.zone_name}</option>`;
                } else {
                    assignDriverZone.innerHTML += ` <label class="selectgroup-item">
                        <input type="radio" name="zonelist" onclick="chooseZone('${z.zone_id}')" value="${z.zone_id}" class="selectgroup-input">
                        <span class="selectgroup-button">${z.zone_name}</span>
                        </label>`
                }

            });
            document.getElementById('selectDriver').classList.remove('d-none')
        }, error: xhr => console.log(xhr.responseText)
    })
};


document.getElementById('filterByZones').addEventListener('change', e => {
    $.ajax({
        type: "GET",
        url: `api/get/brgy/filterbyzone?zone_id=${e.target.value}`,
        dataType: "json",
        success: res => {
            loadBrgy(res.result.data)
        }, error: xhr => console.log(xhr.responseText)
    });
});

function show() {
    document.getElementById('filterByZones').style.display = 'block';
}

document.getElementById('selectZones').addEventListener('change', e => {
    $.ajax({
        type: "GET",
        url: `api/get/brgy/list`,
        dataType: "json",
        success: res => {
            const brgyHolder = document.getElementById('assignBrgyHolder');
            brgyHolder.innerHTML = '';

            res.result.data.forEach(data => {
                brgyHolder.innerHTML += `<div class="form-check">
                        <input class="form-check-input" name="brgy" ${data.zone_id == e.target.value ? 'checked' : ''}
                        type="checkbox" ${data.zone_id == null ? '' : (data.zone_id == e.target.value ? '' : 'disabled')}
                        value="${data.brgy_id}" id="addBrgyZone${data.brgy_id}">
                        <label class="form-check-label" for="addBrgyZone${data.brgy_id}">
                          ${data.brgy_name} ${data.zone_id == null ? '' : '(' + data.zone.zone_name + ')'}
                        </label>
                      </div>`
            });
        }, error: xhr => console.log(xhr.responseText)
    });
});


document.getElementById('addAssignBaranggayToZone').addEventListener('click', e => {
    const parent = e.target.parentNode;
    parent.classList.remove('d-flex');
    parent.classList.add('d-none');

    const addDiv = document.getElementById('addAssignedBrgyDiv');
    const table = document.getElementById('brgyListDiv');
    const closeDiv = document.getElementById('closeAssignBaranggayToZone').parentNode;
    const filter = document.getElementById('filterByZones').parentNode;
    const submit = document.getElementById('addBrgyToZone');

    addDiv.classList.remove('d-none');
    table.classList.add('d-none');
    closeDiv.classList.remove('d-none');
    closeDiv.classList.add('d-flex');
    filter.classList.add('d-none');
    submit.classList.remove('d-none');
});

document.getElementById('closeAssignBaranggayToZone').addEventListener('click', e => {
    const parent = e.target.parentNode;
    parent.classList.remove('d-flex');
    parent.classList.add('d-none');

    const addDiv = document.getElementById('addAssignedBrgyDiv');
    const table = document.getElementById('brgyListDiv');
    const addBtn = document.getElementById('addAssignBaranggayToZone').parentNode;
    const filter = document.getElementById('filterByZones').parentNode;
    const submit = document.getElementById('addBrgyToZone');

    addDiv.classList.add('d-none');
    table.classList.remove('d-none');
    addBtn.classList.remove('d-none');
    addBtn.classList.add('d-flex');
    submit.classList.add('d-none');
    filter.classList.remove('d-none');
});

document.getElementById('addBrgyToZone').addEventListener('click', async () => {
    const container = document.getElementById('assignBrgyHolder');

    const checkedCheckboxes = container.querySelectorAll('input[type="checkbox"]:checked');

    const checkedValues = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

    const csrf = await getCSRF();
    const data = checkedValues.join(',');
    load.on();
    $.ajax({
        type: "POST",
        url: "/api/post/zone/addbrgy",
        data: { "_token": csrf, "brgy": data, "zone": getVal('selectZones') },
        success: res => {
            load.off();
            parseResult(res);
            clicked('closeUpdateZoneModal');

            const addBtn = document.getElementById('addAssignBaranggayToZone').parentNode;
            const addDiv = document.getElementById('addAssignedBrgyDiv');
            const table = document.getElementById('brgyListDiv');
            const closeDiv = document.getElementById('closeAssignBaranggayToZone').parentNode;
            const filter = document.getElementById('filterByZones').parentNode;
            const submit = document.getElementById('addBrgyToZone');

            addDiv.classList.add('d-none');
            table.classList.remove('d-none');
            closeDiv.classList.add('d-none');
            closeDiv.classList.remove('d-flex');
            filter.classList.remove('d-none');
            submit.classList.add('d-none');
            addBtn.classList.remove('d-none');
            addBtn.classList.add('d-flex');

            const val = ` <div class="w-100 d-flex justify-content-center align-items-center flex-column">
                        <img width="50" height="50" src="https://img.icons8.com/ios/50/nothing-found.png" alt="nothing-found"/>
                        <h3 class="text-muted">Nothing to show</h3>
                        <small class="text-muted">Select Zone to show all baranggay list</small>
                    </div>`;

            setHtml('assignBrgyHolder', val);

            LoadMap();
        }, error: xhr => {
            console.log(xhr.responseText)
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeUpdateZoneModal');
        }
    })
});


function loadDriver() {
    $.ajax({
        type: "GET",
        url: "/api/get/truckdriver/getdriverbyzone",
        dataType: "json",
        success: res => {
            const main = document.getElementById('driverListMain');
            const standBy = document.getElementById('driverListStandby');
            main.innerHTML = `<option disabled selected value="">-----No Driver Selected-----</option>`
            standBy.innerHTML = `<option disabled selected value="">-----No Driver Selected-----</option>`

            res.result.data.forEach(data => {
                main.innerHTML += `<option ${data.zone ? 'disabled' : ''} value="${data.td_id}">${data.name} ${data.zone ? '(' + data.zone.type + ' @ ' + data.zone.zone_name + ')' : ''}</option>`;
                standBy.innerHTML += `<option ${data.zone ? 'disabled' : ''} value="${data.td_id}">${data.name} ${data.zone ? '(' + data.zone.type + ' @ ' + data.zone.zone_name + ')' : ''}</option>`
            });
        }, error: xhr => {
            console.log(xhr.responseText)
            parseResult(JSON.parse(xhr.responseText));
        }
    })
}

document.getElementById('driverListMain').addEventListener('change', e => {
    disableSelectDriverCounterPart('driverListStandby', e.target.value, 'Main Driver');
});


document.getElementById('driverListStandby').addEventListener('change', e => {
    disableSelectDriverCounterPart('driverListMain', e.target.value, 'Standby Driver');
});

function disableSelectDriverCounterPart(id, value, type) {
    const main = document.getElementById(id);

    for (let o = 0; o < main.children.length; o++) {
        const checkVal = main.children[o].textContent.split(' ');
        if (main.children[o].disabled == true && main.children[o].value != "" && !checkVal.includes('@')) {
            main.children[o].disabled = false;
            const getText = main.children[o].textContent.split('-');
            main.children[o].textContent = getText[0];
        }
    }

    for (let i = 0; i < main.children.length; i++) {
        const checkVal = main.children[i].textContent.split(' ');
        if (value == main.children[i].value && !checkVal.includes('@')) {
            main.children[i].disabled = true;
            main.children[i].textContent += `-(${type})`;

        }
    }
}

document.getElementById('saveDriverToZone').addEventListener('click', async () => {
    const zoneSelect = document.querySelector('input[name="zonelist"]:checked');

    const mainDriver = document.getElementById('driverListMain').value;
    const standByDriver = document.getElementById('driverListStandby').value;

    const schedDays = document.querySelectorAll(`input[name="schedDays"]`);
    const collectionStart = document.getElementById('collectionStart');
    const collectionEnd = document.getElementById('collectionEnd');

    const schedDaysValues = Array.from(schedDays)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);



    let selectedZone;
    let validity = 0;

    if (zoneSelect) {
        selectedZone = zoneSelect.value;
        isShow('noZoneSelected', false, 'block');
        validity++;
    } else {
        isShow('noZoneSelected', true, 'block');
    }

    if (mainDriver != "") {
        validity++;
        isShow('noMainDriverSelected', false, 'block');
    } else {
        isShow('noMainDriverSelected', true, 'block');
    }

    if (standByDriver != "") {
        validity++;
        isShow('noStandbyDriverSelected', false, 'block');
    } else {
        isShow('noStandbyDriverSelected', true, 'block');
    }


    if (validity == 3) {
        load.on();

        const csrf = await getCSRF();

        $.ajax({
            type: "POST",
            url: "/api/post/truckdriver/driverassignedzone",
            data: {
                "_token": csrf,
                "zone": selectedZone,
                "maindriver": `${mainDriver}-Main Driver`,
                "standbydriver": `${standByDriver}-Standby Driver`,
                "sched_days": schedDaysValues.length == 0 ? "everyday" : schedDaysValues.join(','),
                "collection_start": collectionStart.value == "" ? "05:00" : collectionStart.value,
                "collection_end": collectionEnd.value == "" ? "15:00" : collectionEnd.value
            },
            success: res => {
                parseResult(res);
                load.off();
                clicked('closeAssignDriver');
            }, error: xhr => {
                console.log(xhr.responseText);
                parseResult(JSON.parse(xhr.responseText));
                load.off();
                clicked('closeAssignDriver');
            }
        })
    }
});
// Array to store active markers
let activeMarkers = [];

 function updateRouteStatus(route) {
    const data = route.data;
    const table = document.getElementById('activeDriverTable');
    table.innerHTML = '';

    // Remove existing markers from the map
    activeMarkers.forEach(marker => marker.remove());
    activeMarkers = []; // Clear the marker array

    data.forEach(async e => {
        const splitCoord = e.ad_coordinates.split(',');
        const markerEl = document.createElement('div');
        markerEl.className = 'custom-marker';

        // Add Font Awesome icon to the custom marker
        markerEl.innerHTML = `
            <div style="text-align: center;">
                <i class="fas fa-truck-moving" style="font-size: 24px; color: #6610f2;"></i>
                <p style="margin: 0; font-size: 14px; color: black;">${e.driver.name}</p>
            </div>`;
        
        const coord = [parseFloat(splitCoord[0]), parseFloat(splitCoord[1])];

        // Create a new marker and add it to the map
        const marker = new mapboxgl.Marker(markerEl)
            .setLngLat(coord) // Set marker coordinates
            .addTo(map); // Add marker to the map

        // Store the created marker in the activeMarkers array
        activeMarkers.push(marker);
        const location = await getPlaceName(coord[1], coord[0]);
        // Update the table with driver information
        table.innerHTML += `
            <tr>
                <td>${e.driver.name}</td>
                <td>${e.truck.model}</td>
                <td>${e.truck.can_carry}(Tons)</td>
                <td>${e.zone.zone_name}</td>
                 <td>${location}</td>
                <td><button onclick="viewDriverOnMap(${coord[0]}, ${coord[1]})" class="btn btn-label-primary btn-round">View Location</button></td>
            </tr>`;
    });
}

// Function to fly the map to the specified driver's location
function viewDriverOnMap(lat, lng) {
    map.flyTo({
        center: [lat, lng], // Coordinates to fly to
        zoom: 14, // Zoom level (adjust as needed)
        speed: 1.5, // Speed of the animation (default is 1.2)
        curve: 1, // Controls the smoothness of the animation
        essential: true // This makes the animation a required transition
    });
}

function chooseZone(id) {
    $.ajax({
        type: "GET",
        url: `/api/get/truckdriver/getschedule?zone=${id}`,
        dataType: "json",
        success: res => {
            isShow('manageSchedules', true, 'block');
            const checkboxDisable = document.querySelectorAll(`input[name="schedDays"]`);
            const collectionStart = document.getElementById('collectionStart');
            const collectionEnd = document.getElementById('collectionEnd');

            collectionStart.value = "";
            collectionEnd.value = "";

            checkboxDisable.forEach(element => {
                element.checked = false;
            });
            if (res.result.data.length == 0) {
                isShow('manageSchedules', false, 'block');
                return;
            }

            const data = res.result.data[0];

            if (data.days != "everyday") {
                const scheduleDays = data.days.split(',');
                scheduleDays.forEach(day => {
                    // Use querySelector to select the checkbox with the matching value
                    const checkbox = document.querySelector(`input[name="schedDays"][value="${day}"]`);

                    // If the checkbox is found, set it to checked
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });

                collectionStart.value = data.collection_start;
                collectionEnd.value = data.collection_end;
            }

        }, error: xhr => console.log(xhr.responseText)
    })
}

document.getElementById('addWaypointBtn').addEventListener('click', () => {
    loadZones('assign');
    
    isShow('waypointsTableDiv', false, 'block');
});

document.getElementById('addWaypointToZone').addEventListener('click', async () => {
    const select = document.getElementById('selectZoneAddWaypoint');
    const splitSelect = select.value.split('-');
    const zoneData = geoDataSilayGlobal.filter(x => x.properties.zone == splitSelect[0]);
    selectedZoneWaypoints = splitSelect[0];
    
    const existWaypointsReq = await $.ajax({
        type: "GET",
        url: `/api/get/zone/getallwaypoint?zone=${selectedZoneWaypoints}`,
        dataType: "json"
    });

    isShow('activeTableDiv', false, 'block');

    const existWaypointRes = await existWaypointsReq;

    // Count existing waypoints per barangay
    const existingWaypointCount = {};

    existWaypointRes.result.data.forEach(waypoint => {
        const brgyId = waypoint.brgy_id;
        if (!waypoints[waypoint.brgy_name]) {
            waypoints[waypoint.brgy_name] = [];
        }
        
        waypoints[waypoint.brgy_name].push([parseFloat(waypoint.longitude), parseFloat(waypoint.latitude)]);

        existingWaypointCount[brgyId] = (existingWaypointCount[brgyId] || 0) + 1;
    });

    // Initialize map
    map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [123.0585, 10.8039],
        zoom: 11,
    });

    const zones = {
        type: 'FeatureCollection',
        features: zoneData,
    };

    map.on('load', () => {
        map.addSource('zonesEdit', {
            type: 'geojson',
            data: zones,
        });

        map.addLayer({
            id: 'zonesEdit',
            type: 'fill',
            source: 'zonesEdit',
            layout: {},
            paint: {
                'fill-color': ['get', 'color'],
                'fill-opacity': 0.5,
            },
        });
    });

    // Add existing waypoints to the map
    existWaypointRes.result.data.forEach(cd => addWaypoint([cd.longitude, cd.latitude], cd.brgy_id));

    // Show necessary elements
    isShow('headerMenu', false, 'block');
    isShow('closeEditWaypointPage', true, 'block');

    // Handle map click to add new waypoints
    map.on('click', async (e) => {
        if (selectedBarangay.length === 0) {
            $.notify({ 'message': `No Selected Zone`, 'icon': 'fas fa-exclamation-triangle' }, {
                type: 'danger',
                placement: {
                    from: 'top',
                    align: 'right',
                },
                time: 1000,
                delay: 2000,
            });
            return;
        }

        const coordinates = [e.lngLat.lng, e.lngLat.lat];

        const features = map.queryRenderedFeatures(e.point, {
            layers: ['zonesEdit'],
        });

        const selectedFeature = features.find((feature) =>
            selectedBarangay.some(([name]) => name === feature.properties.name)
        );

        if (selectedFeature) {
            const brgyName = selectedFeature.properties.name;
            const maxWaypoint = selectedFeature.properties.max_waypoint;

            // Determine adjusted max waypoints for the selected barangay
            const brgyId = selectedFeature.properties.brgy_id; // Assume brgy_id is available in properties
            const existingCount = existingWaypointCount[brgyId] || 0;
            const adjustedMaxWaypoint = maxWaypoint - existingCount;

            // Initialize waypoint count if not set
            if (!waypoints[brgyName]) {
                waypoints[brgyName] = [];
            }

            // Check if the limit is reached before adding a new waypoint
            if (waypoints[brgyName].length < adjustedMaxWaypoint) {
                addWaypoint(coordinates, brgyName);
                waypoints[brgyName].push(coordinates);

                // Update the waypoint display count
                document.getElementById(`waypoint_${brgyName}`).textContent = waypoints[brgyName].length;

                $.notify({ 'message': `Waypoint added to ${brgyName}`, 'icon': 'fas fa-map-marker-alt' }, {
                    type: 'success',
                    placement: {
                        from: 'top',
                        align: 'right',
                    },
                    time: 1000,
                    delay: 2000,
                });
            } else {
                $.notify({ 'message': `Maximum waypoints reached for ${brgyName}`, 'icon': 'fas fa-exclamation-triangle' }, {
                    type: 'danger',
                    placement: {
                        from: 'top',
                        align: 'right',
                    },
                    time: 1000,
                    delay: 2000,
                });
            }
        } else {
            $.notify({ 'message': `You can't add waypoints outside the selected barangay zone`, 'icon': 'fas fa-exclamation-triangle' }, {
                type: 'danger',
                placement: {
                    from: 'top',
                    align: 'right',
                },
                time: 1000,
                delay: 2000,
            });
        }

        console.log(waypoints);
    });

    clicked('closeAddWaypoints');
    isShow('addZoneEditDetailsDiv', true, 'block');
    setText('addWaypointDetailHeader', splitSelect[1]);
    const brgyList = document.getElementById('addWaypointBrgyList');
    brgyList.innerHTML = '';
    selectedBarangay.length = 0;
    if (zoneData.length > 0) {
        zoneData.forEach(zd => {
            brgyList.innerHTML += `<li>${zd.properties.name} - Waypoints: <span id="waypoint_${zd.properties.name}">0</span>/${zd.properties.max_waypoint}</li>`;
            selectedBarangay.push([zd.properties.name, zd.properties.max_waypoint, zd.properties.brgy_id]);
        });
    } else {
        brgyList.innerHTML = '<li>No Brgy Added</li>';
    }
    const brgyNames =  existWaypointRes.result.data.filter((item, index, self) =>
        index === self.findIndex((t) => t.brgy_name === item.brgy_name)
      );

    brgyNames.forEach(brgy => {
        const element = document.getElementById(`waypoint_${brgy.brgy_name}`);

        const numOfWaypoint = existWaypointRes.result.data.filter(x=> x.brgy_name === brgy.brgy_name).length;

        element.textContent = numOfWaypoint;
    })
    $.notify({ 'message': `Editing ${splitSelect[1]} Waypoints`, 'icon': 'fas fa-exclamation-triangle' }, {
        type: 'success',
        placement: {
            from: 'top',
            align: 'right',
        },
        time: 1000,
        delay: 2000,
    });
});

function addWaypoint(coordinates, brgyName) {
    const marker = new mapboxgl.Marker()
        .setLngLat(coordinates)
        .addTo(map);
    waypointMarker.push(marker);
}

function redoMap() {
    // Remove all markers from the map
    waypointMarker.forEach(marker => marker.remove());
    waypointMarker = []; // Clear the markers array

    // Clear the waypoints data
    waypoints = {};
    selectedBarangay.forEach(([name, maxWaypoint]) => {
        document.getElementById(`waypoint_${name}`).textContent = '0';
    });

    $.notify({ 'message': `Map reset, all waypoints removed.`, 'icon': 'fas fa-redo' }, {
        type: 'info',
        placement: {
            from: 'top',
            align: 'right',
        },
        time: 1000,
        delay: 2000,
    });
}

document.getElementById('redoEditWaypoints').addEventListener('click', ()=> {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Redo Waypoint Selection!"
      }).then((result) => {
        if (result.isConfirmed) {
            redoMap();
        }
      });
       
});

document.getElementById('saveEditWaypoints').addEventListener('click', async ()=> {
    if(Object.keys(waypoints).length === 0){
        $.notify({ 'message': `No waypoint present in the map please add atleast 1.`, 'icon': 'fas fa-exclamation-triangle' }, {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'right',
            },
            time: 1000,
            delay: 2000,
        });

        return;
    }

    if(selectedBarangay.length === 0){
        $.notify({ 'message': `No baranggay added in this zone please add 1 first.`, 'icon': 'fas fa-exclamation-triangle' }, {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'right',
            },
            time: 1000,
            delay: 2000,
        });

    }
    load.on();
    console.log(waypoints);
    const csrf = await getCSRF();

    $.ajax({
        type: "POST",
        url: "/api/post/zone/addwaypoint",
        data: {"_token": csrf, "brgy": selectedBarangay, "waypoints": waypoints, "zone": selectedZoneWaypoints },
        success: res=> {
            load.off();
            parseResult(res);
        }, error: xhr => {
            console.log(xhr.responseText);
            parseResult(JSON.parse(xhr.responseText));
            load.off();
        }
    })
});


document.getElementById('closeEditWaypointPage').addEventListener('click', ()=> {
    isShow('headerMenu',true, 'block');
    isShow('closeEditWaypointPage', false, 'block');
    isShow('addZoneEditDetailsDiv', false, 'block');
    isShow('activeTableDiv', true, 'block');
    LoadMap();
});


document.getElementById('selectZoneAddWaypoint').addEventListener('change', async (e) => {
    if ($.fn.DataTable.isDataTable('#waypointsTable')) {
        $('#waypointsTable').DataTable().clear().destroy();
    }

    try {
        const response = await $.ajax({
            type: "GET",
            url: `/api/get/zone/getallwaypoint?zone=${e.target.value.split('-')[0]}&type=admin&id=null`,
            dataType: "json"
        });

        isShow('waypointsTableDiv', true, 'block');

        // Extract the data and create a temporary array for promises
        const waypointsData = response.result.data;

        // Create an array of promises to fetch place names
        const placeNamePromises = waypointsData.map(async (data) => {
            const placeName = await getPlaceName(data.latitude, data.longitude);
            return { ...data, placeName }; // Attach place name to the data object
        });

        // Wait for all promises to resolve
        const waypointsWithPlaceNames = await Promise.all(placeNamePromises);

        // Initialize DataTable with the new data
        $('#waypointsTable').DataTable({
            data: waypointsWithPlaceNames,
            columns: [
                { data: null, orderable: false },
                { data: 'brgy_name' },
                { data: 'placeName' }, // Now directly using placeName from the resolved promise
                { data: null, render: data => `${data.longitude} - ${data.latitude}` }
            ],
            rowCallback: function(row, data, index) {
                // Set the waypoint number in the first column
                $('td:eq(0)', row).html('Waypoint ' + (index + 1));
            }
        });
    } catch (error) {
        console.error("Error fetching waypoints:", error);
    }
});

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


document.getElementById('manageSchedules').addEventListener('click', ()=> {
    isShow('assignScheduleDiv', false, 'block');
    isShow('manageScheduleDiv', true, 'block');
});

document.getElementById('closeManageSchedule').addEventListener('click', ()=> {
    isShow('assignScheduleDiv', true, 'block');
    isShow('manageScheduleDiv', false, 'block');
});