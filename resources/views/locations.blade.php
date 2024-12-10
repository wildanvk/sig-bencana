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
    <div id="map"></div>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-6.889978338487142, 109.67363486279189], 13); // Pusat peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Ambil data lokasi dari API
        fetch('/api/locations')
            .then(response => response.json())
            .then(data => {
                data.forEach(location => {
                    const coordinates = location.coordinates.replace('POINT(', '').replace(')', '').split(' ');
                    L.marker([coordinates[1], coordinates[0]])
                        .addTo(map)
                        .bindPopup(location.name);
                });
            });
    </script>
</body>

</html>