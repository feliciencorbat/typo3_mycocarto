const mapEl  = document.querySelector('#map');
const latitude = mapEl.dataset.latitude;
const longitude = mapEl.dataset.longitude;

let layer = ga.layer.create('ch.swisstopo.pixelkarte-farbe');

// Create a map
let map = new ga.Map({
    target: 'map',
    layers: [layer],
    view: new ol.View({
        resolution: 5,
        center: [latitude, longitude]
    })
});

// Create a feature
let iconFeature = new ol.Feature({
    geometry: new ol.geom.Point([latitude, longitude]),
    name: "Observation"
});

// Associate an icon style to the feature
let iconStyle = new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [.5, 1],
        anchorXUnits: 'fraction',
        anchorYUnits: 'fraction',
        src: 'https://mycocartotypo.ddev.site:8043/fileadmin/logos/marker.png'
    }),
});

iconFeature.setStyle(iconStyle);

// Create a vector source
let vectorSource = new ol.source.Vector({
    features: [iconFeature]
});

// Create a vector layer using the vector source
let vectorLayer = new ol.layer.Vector({
    source: vectorSource
});

// Add the vector layer in the map
map.addLayer(vectorLayer);

