const mapEl  = document.querySelector('#map');
const latitude = mapEl.dataset.latitude;
const longitude = mapEl.dataset.longitude;
const species = mapEl.dataset.species;
const author = mapEl.dataset.author;

// transform CH1903 to WGS84
let coordsWGS84 = Swisstopo.CHtoWGS(latitude, longitude);

let mapOptions = {
    center:[coordsWGS84[1], coordsWGS84[0]],
    zoom:15
}

let map = new L.map('map' , mapOptions);

let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);

let customIcon = {
    iconUrl:"https://mycocartotypo.ddev.site:8043/fileadmin/logos/marker.png",
    iconSize:[40,40]
}

let myIcon = L.icon(customIcon);
//let myIcon = L.divIcon();

let iconOptions = {
    title:"company name",
    draggable:true,
    icon:myIcon
}

let marker = new L.Marker([coordsWGS84[1], coordsWGS84[0]] , iconOptions);
marker.addTo(map);
marker.bindPopup(`<p><i>${species}</i> ${author}</p>`);
