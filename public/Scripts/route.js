mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
let map;
let clickCount = 0;
let directions;
let markers = [];
let coordinatesList = [];
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
    try {
        const placeName = await getPlaceName(coordinates);

        coordinatesList.push(`${coordinates[0]}-${coordinates[1]}`);

        const list = document.getElementById('waypointListTable');

        clickCount == 1 ? list.innerHTML = '' : null;

        list.innerHTML += `<tr>
                    <td>${clickCount}</td>
                     <td>${coordinates[0]}, ${coordinates[1]}</td>
                      <td>${placeName}</td>
                    </tr>`;
    } catch (error) {
        console.error('Error fetching location name:', error);
    }

    const marker = new mapboxgl.Marker()
        .setLngLat(coordinates)
        .addTo(map);

    markers.push(marker);

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
    coordinatesList.length = 0;
    isVisible('none', 'clearWaypoints')
}
let selectedScheduleType = 'daily';
document.getElementById('saveRoute').addEventListener('click', async () => {
    load.on();

    const sched = parseSchedule(selectedScheduleType);
    if (sched[0] == 'error') {
        load.off();
        showError(sched[1]);
        return;
    }
    let parseCoordinate = '';
    coordinatesList.forEach((data) => {
        parseCoordinate += `${data},`;
    });
    const routeName = getVal('routeName');
    const csrf = await getCSRF();
    const data = {
        "_token": csrf,
        "route_name": routeName,
        "coordinates": parseCoordinate,
        "assigned_driver": getVal('selectDriver'),
        "schedule": sched
    }

    $.ajax({
        type: "POST",
        url: getApi('routes', 'add', 'post'),
        data: data,
        success: res => {
            load.off()
            parseResult(res)
            removeAllWaypoints()
            setValue('routeName', '');
            setValue('selectDriver', 'none');
            loadAllRoute();
            const empty = ` <tr>
                    <td colspan="3" class="text-center">No Waypoint Added</td>
                  </tr>`;
            setHtml('waypointListTable', empty);
        },
        error: xhr => {
            load.off();
            parseResult(JSON.parse(xhr.responseText));
        }
    });

});

function loadAllRoute() {

    if ($.fn.DataTable.isDataTable('#routes')) {
        $('#routes').DataTable().clear().destroy();
    }

    $.ajax({
        type: "GET",
        url: getApi('routes', 'list', 'get'),
        dataType: "json",
        success: res => {

            const card = document.getElementById('addRouteCard');
            card.classList.add('d-none');
            $('#routes').DataTable({
                data: res.result.data,
                columns: [
                    { title: "Route Name", data: "r_name" },
                    {
                        title: "Schedule", data: null,
                        render: data => {
                            const sched = processSchedule(data.r_schedule);
                            return sched;
                        }
                    },
                    {
                        title: "Status", data: null,
                        render: data => {
                            return `<span class="badge badge-${data.r_status == 1 ? 'success' : 'danger'}">${data.r_status == 1 ? 'Active' : 'Inactive'}</span>`
                        }
                    },
                    { title: "Assigned Driver", data: "truck_driver" },
                    {
                        title: "Action", data: null,
                        render: data => {
                            return `
                    <div class="d-flex gap-1">
                <button onclick="EditRoute('${data.r_id}')" class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
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

document.addEventListener('DOMContentLoaded', () => {
    loadAllRoute();
});

async function EditRoute(id) {
    const card = document.getElementById('addRouteCard');

    const classes = Array.from(card.classList);

    if (classes.includes('d-none')) {
        card.classList.remove('d-none');
    } else {
        card.classList.add('d-none');
    }


    $.ajax({
        type: "GET",
        url: `/api/get/routes/details?id=${id}`,
        dataType: "json",
        success: res => {
            console.log(res);
            removeAllWaypoints();
            const data = res.result.data;

            const coords = data.r_coordinates.split(',');
            coords.pop();
            coords.forEach((cd) => {
                const coordinates = cd.split('-');
                const marker = new mapboxgl.Marker()
                    .setLngLat(coordinates)
                    .addTo(map);

                markers.push(marker);

                const waypoints = directions.getWaypoints();
                if (waypoints.length < 25) {
                    directions.addWaypoint(waypoints.length, coordinates);
                }
            });

            setValue('routeName', data.r_name);
            isVisible('flex', 'clearWaypoints');
        },
        error: xhr => console.log(xhr.responseText)
    });
}

function RemoveRoute(id) {
    confirmAction().then(async confirm => {
        if (confirm) {
            load.on();

            const csrf = await getCSRF();

            $.ajax({
                type: "POST",
                url: getApi('routes', 'delete', 'post'),
                data: { "_token": csrf, "id": id },
                success: res => {
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

document.querySelectorAll('input[name="schedType"]').forEach((radio) => {
    radio.addEventListener('click', (e) => {

        isShow('weeklySched', false, 'block');
        isShow('dailySched', false, 'block');
        isShow('monthlySched', false, 'block');
        isShow('weekendHolidaySched', false, 'block');

        switch (e.target.value) {
            case "daily":
                isShow('dailySched', true, 'block');
                selectedScheduleType = "daily";
                break;
            case "weekly":
                isShow('weeklySched', true, 'block');
                selectedScheduleType = "weekly";
                break;
            case "monthly":
                isShow('monthlySched', true, 'block');
                selectedScheduleType = "monthly";
                break;
            default:
                isShow('weekendHolidaySched', true, 'block');
                selectedScheduleType = "weekendHoliday";
                break;
        }
    });
});

document.getElementById('noDurationDaily').addEventListener('click', () => {
    isShow('dailyDuration', false);
});

document.getElementById('setDurationDaily').addEventListener('click', () => {
    isShow('dailyDuration', true);
});



document.getElementById('selectMonthlyOptions').addEventListener('change', (e) => {
    if (e.target.value == 'setCustomDate') {
        isShow('setMonthlyCustomDate', true, 'block');
    } else {
        isShow('setMonthlyCustomDate', false, 'block');
    }
});

function processSchedule(sched) {
    const schedule = sched.split('**');

    switch (schedule[0]) {
        case "daily":
            if (schedule[1] == 'noDuration') {
                return "Daily(Every Day)";
            } else {
                return `Daily(${schedule[2]} / ${schedule[3]})`;
            }
        case "weekly":
            let days = '';
            for (let i = 0; i < schedule.length; i++) {
                if (i != 0) {
                    if (schedule[i] != '') {
                        days += `${parseDays(schedule[i])}-`;
                    }
                }
            }
            return `Weekly(${days.slice(0, -1)})`;

        case "monthly":
            if (schedule[1] == "setCustomDate") {
                return `Monthly(${schedule[2]})`;
            }

            return `Monthly(${schedule[1]})`;
        default:
            return schedule[1];
    }
}

function parseDays(num) {
    switch (num) {
        case "1":
            return "Mon";
        case "2":
            return "Tue";
        case "3":
            return "Wed";
        case "4":
            return "Thu";
        case "5":
            return "Fri";
        case "6":
            return "Sat";
        case "7":
            return "Sun";
    }
}

document.getElementById('addRouteBtn').addEventListener('click', (e) => {

    const element = e.target;


    const card = document.getElementById('addRouteCard');

    const classes = Array.from(card.classList);

    if (classes.includes('d-none')) {
        card.classList.remove('d-none');
        element.classList.add('btn-danger');
        element.classList.remove('btn-primary');
        element.textContent = 'X';
    } else {
        element.classList.remove('btn-danger');
        element.classList.add('btn-primary');
        element.textContent = 'Add Route';
        card.classList.add('d-none');
    }
});
