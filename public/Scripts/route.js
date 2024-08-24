mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
let map;
let clickCount = 0;

map = new mapboxgl.Map({
    container: 'map', // container ID
    style: 'mapbox://styles/mapbox/streets-v12', // style URL
    center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
    zoom: 11, // starting zoom
});

const directions = new MapboxDirections({
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
    console.log('Clicked coordinates:', coordinates);
    clickCount++;

    try {
        const placeName = await getPlaceName(coordinates);

        if (clickCount == 1) {
            setText('startRouteSpan', `(A) ${placeName}`);
            setValue('saveRouteStartLongitude', coordinates[0]);
            setValue('saveRouteStartLatitude', coordinates[1]);
        } else {
            setText('endRouteSpan', `(B) ${placeName} `);
            setValue('saveRouteEndLongitude', coordinates[0]);
            setValue('saveRouteEndLatitude', coordinates[1]);
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
    removeAllWaypoints(directions);
});

function removeAllWaypoints(directions) {
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

    isVisible('none', 'clearWaypoints')
}

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch(getApi('truckdriver', 'list', 'get'));
        const data = await response.json();
        const driver = data.result;

        console.log('Driver data:', driver); // Check if this logs the expected 45 items

        if (!driver || !driver.data) {
            console.error('Driver data is missing or empty.');
            return;
        }

        const select = document.getElementById('selectDriver');
        select.innerHTML = '<option selected disabled value="">-----Select Driver-----</option>';

        driver.data.forEach(e => {
            console.log('Option data:', e); // Verify each item before adding to the DOM
            select.innerHTML += `
                <option value="${e.td_id}">${e.name} - ${e.dumptruck.model}/${e.dumptruck.can_carry}</option>
            `;
        });

        console.log('Options added to select element.');
    } catch (error) {
        console.error('Error fetching driver data:', error);
    }
});
