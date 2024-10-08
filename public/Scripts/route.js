mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
// import { geoDataSilay } from "./SilayBrgyGeoData.js ";

window.onload = LoadMap();

async function getGeoData(){
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

        if(d.zone){
            properties.color = d.zone.zone_color;
        }else{
            properties.color = d.property_color;
        }


        geometry.coordinates = [[coordinates]];
        mainData.geometry = geometry;
        mainData.properties = properties;

        geoData.push(mainData);
    });

    return geoData;
}

async function getDumpSiteLocation(){
    const response = await fetch('/api/get/settings/getval?context=dumpsite_location');

    const result = await response.json();
    const data = result.result.data.settings_value;

    return data;
}

let marker; // Variable to store the marker
let isSelecting = false; // Flag to track if the user is selecting a location
let circleLayerId = 'marker-radius'; // ID for the circle layer
let map;
async function LoadMap(){

    const geoDataSilay = await getGeoData();
    const dumpsiteLocation = await getDumpSiteLocation();
    const dumpsiteCoord = dumpsiteLocation.split(',');

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
        data: {"_token": csrf, "context": "dumpsite_location", "longitude": longitude, "latitude": latitude},
        success: res=> {
            parseResult(res);
            load.off();
        }, error: xhr =>{
            console.log(xhr.responseText);
            parseResult(JSON.parse(xhr.responseText)    )
        }
      });
    }
  });

}

document.getElementById('changeDumpsiteLocation').addEventListener('click', e =>{
    isSelecting = !isSelecting;

    if(isSelecting){
        $.notify({'message':'You Can now change the location of the dumpsite','icon': 'icon-check' }, {
            type: 'success',
            placement: {
              from: 'top',
              align: 'right',
            },
            time: 1000,
            delay: 2000,
            });

        e.target.innerHTML = `<i class="fa fa-edit"></i> Editing.... Click Again to cancel`

    }else{
        $.notify({'message':'Editing Cancelled','icon': 'icon-check' }, {
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
document.getElementById('updateZonesBtn').addEventListener('click', ()=> {
    getAllBrgyList();
    loadZones('update');
})

function getAllBrgyList() {
    $.ajax({
        type: 'GET',
        url: "/api/get/brgy/list",
        dataType: "json",
        success: res=> {
           loadBrgy(res.result.data);

        }, error: xhr=> console.log(xhr.responseText)
    });
}

function loadBrgy(data){
    if(!$.fn.DataTable.isDataTable('#silayBrgy')){
        silayBrgyTable = $('#silayBrgy').DataTable({
            data: data,
            pageLength: 5,
            columns: [
                {'title': "Baranggay", data: "brgy_name"},
                {'title': "Assigned Zone", data: null,
                    render: data => {
                        return data.zone_id == null ? 'Not Set' : data.zone_id
                    }
                }
            ]
        });
    }else{
        silayBrgyTable.clear().rows.add(data).draw();
    }

}

document.getElementById('assignDriverBtn').addEventListener('click', () => {
    loadZones('add');
    loadDriver();
});

function loadZones(type){
    $.ajax({
        type: 'GET',
        url: "/api/get/zone/list",
        dataType: "json",
        success: res=> {
            const zones = document.getElementById('filterByZones');
            const addZones = document.getElementById('selectZones');
            zones.innerHTML = `<option value="all">All Baranggays</option>`;
            addZones.innerHTML = '<option value="" disabled selected>------Select a Zone-------</option>';

            console.log(res);
            const assignDriverZone = document.getElementById('addDriverZoneList');
            assignDriverZone.innerHTML = '';

            res.result.data.forEach( z => {
                if(type == 'update'){
                    zones.innerHTML += `<option value="${z.zone_id}">${z.zone_name}</option>`;
                    addZones.innerHTML += `<option value="${z.zone_id}">${z.zone_name}</option>`;
                }else{
                    assignDriverZone.innerHTML += ` <label class="selectgroup-item">
                        <input type="radio" name="zonelist" value="${z.zone_id}" class="selectgroup-input">
                        <span class="selectgroup-button">${z.zone_name}</span>
                        </label>`
                }

              });


              document.getElementById('selectDriver').classList.remove('d-none')
        }, error: xhr=> console.log(xhr.responseText)
    })
};


document.getElementById('filterByZones').addEventListener('change', e => {
    $.ajax({
        type: "GET",
        url: `api/get/brgy/filterbyzone?zone_id=${e.target.value}`,
        dataType:"json",
        success: res=> {
            loadBrgy(res.result.data)
        }, error: xhr=> console.log(xhr.responseText)
    });
});

function show(){
    document.getElementById('filterByZones').style.display = 'block';
    console.log('as')
}

document.getElementById('selectZones').addEventListener('change', e => {
    $.ajax({
        type: "GET",
        url: `api/get/brgy/list`,
        dataType:"json",
        success: res=> {
            const brgyHolder = document.getElementById('assignBrgyHolder');
            brgyHolder.innerHTML = '';

            res.result.data.forEach(data=> {
                brgyHolder.innerHTML +=  `<div class="form-check">
                        <input class="form-check-input" name="brgy" ${data.zone_id == e.target.value ? 'checked' : ''}
                        type="checkbox" ${data.zone_id == null ? '' : (data.zone_id == e.target.value ? '' : 'disabled')}
                        value="${data.brgy_id}" id="addBrgyZone${data.brgy_id}">
                        <label class="form-check-label" for="addBrgyZone${data.brgy_id}">
                          ${data.brgy_name} ${data.zone_id == null  ? '' : '(' + data.zone.zone_name + ')'}
                        </label>
                      </div>`
            });
        }, error: xhr=> console.log(xhr.responseText)
    });
});


document.getElementById('addAssignBaranggayToZone').addEventListener('click', e=> {
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

document.getElementById('closeAssignBaranggayToZone').addEventListener('click', e=> {
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

document.getElementById('addBrgyToZone').addEventListener('click', async ()=> {
    const container = document.getElementById('assignBrgyHolder');

    const checkedCheckboxes = container.querySelectorAll('input[type="checkbox"]:checked');

    const checkedValues = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

    const csrf = await getCSRF();
    const data = checkedValues.join(',');
    load.on();
    $.ajax({
        type: "POST",
        url: "/api/post/zone/addbrgy",
        data: {"_token": csrf, "brgy": data, "zone": getVal('selectZones')},
        success: res=> {
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
        },error: xhr=> {
            console.log(xhr.responseText)
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeUpdateZoneModal');
        }
    })
});


function loadDriver(){
    $.ajax({
        type:"GET",
        url: "/api/get/truckdriver/getdriverbyzone",
        dataType:"json",
        success: res => {
            console.log(res);
            const main = document.getElementById('driverListMain');
            const standBy = document.getElementById('driverListStandby');
            main.innerHTML = `<option disabled selected value="">-----No Driver Selected-----</option>`
            standBy.innerHTML = `<option disabled selected value="">-----No Driver Selected-----</option>`

            res.result.data.forEach(data=> {
                main.innerHTML += `<option ${data.zone ? 'disabled': ''} value="${data.td_id}">${data.name} ${data.zone ? '(' + data.zone.type + ' @ ' + data.zone.zone_name + ')': ''}</option>`;
                standBy.innerHTML += `<option ${data.zone ? 'disabled': ''} value="${data.td_id}">${data.name} ${data.zone ? '(' + data.zone.type + ' @ ' + data.zone.zone_name + ')': ''}</option>`
            });
        }, error: xhr=> {
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

function disableSelectDriverCounterPart(id, value, type){
    const main = document.getElementById(id);

    for(let o = 0; o < main.children.length; o++){
        const checkVal = main.children[o].textContent.split(' ');
        if(main.children[o].disabled == true && main.children[o].value != "" && !checkVal.includes('@')){
            main.children[o].disabled = false;
            const getText = main.children[o].textContent.split('-');
            main.children[o].textContent = getText[0];
        }
    }

    for(let i = 0; i < main.children.length; i++){
        const checkVal = main.children[i].textContent.split(' ');
        if(value == main.children[i].value && !checkVal.includes('@')){
            main.children[i].disabled = true;
            main.children[i].textContent +=  `-(${type})`;

        }
    }
}

document.getElementById('saveDriverToZone').addEventListener('click', async ()=> {
    const zoneSelect = document.querySelector('input[name="zonelist"]:checked');

    const mainDriver = document.getElementById('driverListMain').value;
    const standByDriver = document.getElementById('driverListStandby').value;

    let selectedZone;
    let validity = 0;
    if(zoneSelect){
        selectedZone = zoneSelect.value;
        isShow('noZoneSelected', false, 'block');
        validity++;
    }else{
        isShow('noZoneSelected', true, 'block');
    }

    if(mainDriver != ""){
        validity++;
        isShow('noMainDriverSelected', false, 'block');
    }else{
        isShow('noMainDriverSelected', true, 'block');
    }

    if(standByDriver != ""){
        validity++;
        isShow('noStandbyDriverSelected', false, 'block');
    }else{
        isShow('noStandbyDriverSelected', true, 'block');
    }

    if(validity == 3){
        load.on();

        const csrf = await getCSRF();

        $.ajax({
            type: "POST",
            url: "/api/post/truckdriver/driverassignedzone",
            data: {
                    "_token": csrf,
                    "zone": selectedZone,
                    "maindriver": `${mainDriver}-Main Driver`,
                    "standbydriver": `${standByDriver}-Standby Driver`
                },
            success: res=> {
                parseResult(res);
                load.off();
                clicked('closeAssignDriver');
            }, error: xhr=> {
                console.log(xhr.responseText);
                parseResult(JSON.parse(xhr.responseText));
                load.off();
                clicked('closeAssignDriver');
            }
        })
    }
});


function updateRouteStatus(route) {
    console.log(route);
    const data = route.data;

    data.forEach(e => {
        const splitCoord = e.ad_coordinates.split(',');
        const markerEl = document.createElement('div');
        markerEl.className = 'custom-marker';

        // Add Font Awesome icon to the custom marker
        markerEl.innerHTML = `<div style="text-align: center;">
        <i class="fas fa-truck-moving" style="font-size: 24px; color: #6610f2;"></i>
        <p style="margin: 0; font-size: 14px; color: black;">${e.driver.name}</p>
        </div>`;
        const coord = [splitCoord[0], splitCoord[1]];

        new mapboxgl.Marker(markerEl)
            .setLngLat(coord) // Set marker coordinates
            .addTo(map); // Add marker to the map
    });
}
