    mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
    let map;
    let directions;
    let markers = [];
    
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
        const coordinates = e.coords.longitude + ', ' + e.coords.latitude;

        const csrf = await getCSRF();

        $.ajax({
            type: "POST",
            url: "/api/post/drivers/updatelocation",
            data: {"_token": csrf, "coordinates": coordinates},
            success: res=> {
                console.log(res);
            }, error: xhr => console.log(xhr.responseText)
        });
        console.log(coordinates);
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

                // Add markers to each waypoint
                coordinates.forEach((coordinate, index) => {
                    const [longitude, latitude] = coordinate.split('-');
                    const marker = new mapboxgl.Marker()
                        .setLngLat([longitude, latitude])
                        .setPopup(new mapboxgl.Popup().setHTML(`Waypoint ${index + 1}`))
                        .addTo(map);
                    markers.push(marker);
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

    document.getElementById('startDrive').addEventListener('click', ()=>{});

