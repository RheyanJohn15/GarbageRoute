mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
let map;
map = new mapboxgl.Map({
    container: 'map', // container ID
    style: 'mapbox://styles/mapbox/streets-v12', // style URL
    center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
    zoom: 11, // starting zoom
});


function updateRouteStatus(route){
    console.log(route);
    const data = route.data;    
  
    data.forEach(e => {
        const splitCoord = e.rp_current_location.split(',');
        const markerEl = document.createElement('div');
        markerEl.className = 'custom-marker';
        
        // Add Font Awesome icon to the custom marker
        markerEl.innerHTML = `<div style="text-align: center;">
                <i class="fas fa-truck-moving" style="font-size: 24px; color: #6610f2;"></i>
                <p style="margin: 0; font-size: 14px; color: black;">${e.drivername}</p>
            </div>`;
        const coord = [splitCoord[0], splitCoord[1]];

        new mapboxgl.Marker(markerEl)
        .setLngLat(coord) // Set marker coordinates
        .addTo(map); // Add marker to the map
    })
}