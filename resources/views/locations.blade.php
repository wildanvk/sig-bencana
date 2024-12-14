<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geospatial Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
        }
    </style>
</head>

<body>
    <h1>Geospatial Map</h1>
    <input type="text" id="latitude-input">
    <input type="text" id="longitude-input">
    <div id="map"></div>

    <script>
        let mapInitialized = false;
        let map;
        let marker;

        document.addEventListener('DOMContentLoaded', function() {
            const latitudeInput = document.getElementById('latitude-input');
            const longitudeInput = document.getElementById('longitude-input');

            const defaultLat = parseFloat(latitudeInput?.value) || -6.8883;
            const defaultLng = parseFloat(longitudeInput?.value) || 109.6784;

            if (!mapInitialized) {
                // Inisialisasi peta hanya jika belum diinisialisasi
                map = L.map('map').setView([defaultLat, defaultLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Tambahkan marker
                marker = L.marker([defaultLat, defaultLng], {
                    draggable: true
                }).addTo(map);

                mapInitialized = true;
            }

            // Listener untuk memperbarui input dari marker
            marker.on('dragend', function(e) {
                const lat = e.target.getLatLng().lat.toFixed(8);
                const lng = e.target.getLatLng().lng.toFixed(8);

                if (latitudeInput) latitudeInput.value = lat;
                if (longitudeInput) longitudeInput.value = lng;
            });

            // Listener untuk memperbarui marker dari input
            latitudeInput.addEventListener('input', function() {
                const lat = parseFloat(latitudeInput.value) || defaultLat;
                const lng = parseFloat(longitudeInput.value) || defaultLng;

                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng]);
                }
            });

            longitudeInput.addEventListener('input', function() {
                const lat = parseFloat(latitudeInput.value) || defaultLat;
                const lng = parseFloat(longitudeInput.value) || defaultLng;

                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng]);
                }
            });
        });
    </script>
</body>

</html>