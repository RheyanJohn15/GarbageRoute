mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
import { geoDataSilay } from "./SilayBrgyGeoData.js ";

const map = new mapboxgl.Map({
  container: 'map', // container ID
  style: 'mapbox://styles/mapbox/streets-v12', // style URL
  center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
  zoom: 11, // starting zoom
});
const zones = {
    type: 'FeatureCollection',
    features: geoDataSilay
  };

map.on('load', () => {
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
  });


