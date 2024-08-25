mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
let map;
let clickCount = 0;
let directions;

map = new mapboxgl.Map({
    container: 'map', // container ID
    style: 'mapbox://styles/mapbox/streets-v12', // style URL
    center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
    zoom: 11, // starting zoom
});

directions = new MapboxDirections({
    accessToken: mapboxgl.accessToken,
    unit: 'metric',
    profile: 'mapbox/driving',
    controls: {
        inputs: false, // Hide input boxes
        instructions: true,
    },
});

map.addControl(directions, 'top-left');

map.on('click', async (e) => {
    const coordinates = [e.lngLat.lng, e.lngLat.lat]; // Convert to array format

    clickCount++;
    console.log(coordinates);
    try {
        const placeName = await getPlaceName(coordinates);
       
        if (clickCount == 1) {
            setText('startRouteSpan', `(A) ${placeName}`);
            setValue('saveRouteStartLongitude', coordinates[0]);
            setValue('saveRouteStartLatitude', coordinates[1]);
            setValue('saveRouteStartLocation', placeName);
        } else {
            setText('endRouteSpan', `(B) ${placeName} `);
            setValue('saveRouteEndLongitude', coordinates[0]);
            setValue('saveRouteEndLatitude', coordinates[1]);
            setValue('saveRouteEndLocation', placeName);
        }
    } catch (error) {
        console.error('Error fetching location name:', error);
    }

    const waypoints = directions.getWaypoints();
    if (waypoints.length < 25) {
        directions.addWaypoint(waypoints.length, coordinates);
    }

    isVisible('flex', 'clearWaypoints');
});

async function getPlaceName(coordinates) {
    const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${coordinates[0]},${coordinates[1]}.json?access_token=${mapboxgl.accessToken}`);
    const data = await response.json();
    return data.features[0]?.place_name || 'Unknown Location';
}

document.getElementById('clearWaypoints').addEventListener('click', () => {
    removeAllWaypoints();
});

function removeAllWaypoints() {
    map.remove();

    // Recreate the map instance
    const newMap = new mapboxgl.Map({
        container: 'map', // container ID
        style: 'mapbox://styles/mapbox/streets-v12', // style URL
        center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
        zoom: 11, // starting zoom
    });

    // Re-add the directions control to the new map
    const newDirections = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/driving',
        controls: {
            inputs: false, // Hide input boxes
            instructions: true,
        },
    });

    newMap.addControl(newDirections, 'top-left');

    // Assign the new map and directions instances to global variables if needed
    map = newMap;
    directions = newDirections;
    setText('startRouteSpan', 'N/A');
    setText('endRouteSpan', 'N/A')
    isVisible('none', 'clearWaypoints')
}


document.getElementById('routeName').addEventListener('input', (e)=> {
   setValue('saveRouteName', e.target.value);
});

document.getElementById('selectDriver').addEventListener('change', (e)=> {
  setValue('saveRouteAssignedTruck', e.target.value);
});

document.getElementById('saveRoute').addEventListener('click', ()=> {
    load.on();
    
    $.ajax({
       type:"POST",
       url: getApi('routes', 'add', 'post'),
       data: $('form#saveRouteForm').serialize(),
       success: res=> {
        load.off()
        parseResult(res)
        removeAllWaypoints()
        setValue('routeName', '');
        setValue('selectDriver', 'none');
        loadAllRoute();
       }, 
       error: xhr => {
        load.off();
        parseResult(JSON.parse(xhr.responseText));
       }
    });

});

function loadAllRoute(){
    
    if ($.fn.DataTable.isDataTable('#routes')) {
        $('#routes').DataTable().clear().destroy();
    }

    $.ajax({
      type:"GET",
      url: getApi('routes', 'list', 'get'),
      dataType: "json",
      success: res=> {
          $('#routes').DataTable({
            data: res.result.data,
            columns: [
                {title: "Route Name", data: "r_name"},
                {title: "Start", data: "r_start_location"},
                {title: "End", data: "r_end_location"},
                {title: "Assigned Driver", data: "truck_driver"},
                {title: "Action" , data: null,
                   render: data=> {
                    return `
                    <div class="d-flex gap-1">
                <button class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
                <button onclick="RemoveRoute('${data.r_id}')" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
              </div>`
                   }, orderable: false
                }
            ],
            pageLength: 5,
          })
      }, error: xhr => console.log(xhr.responseText)
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
   loadAllRoute();
});

function RemoveRoute(id){
   confirmAction().then(async confirm => {
      if(confirm){
        load.on();

        const csrf = await getCSRF();

        $.ajax({
            type: "POST",
            url: getApi('routes', 'delete', 'post'),
            data: {"_token": csrf, "id": id},
            success: res=> {
                load.off();
                parseResult(res);
                loadAllRoute();
            },
            error: xhr => {
                load.off();
                parseResult(JSON.parse(xhr.responseText))
            }
        })
      }
   })
}