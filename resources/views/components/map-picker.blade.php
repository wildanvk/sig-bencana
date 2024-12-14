<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div id="map" wire:ignore style="height: 400px; width: 100%; margin-top: 10px; border-radius: 10px;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let map, marker;
    const latitudeInput = document.getElementById('latitude-input');
    const longitudeInput = document.getElementById('longitude-input');

    const defaultLat = parseFloat(latitudeInput?.value) || -6.8883; // Default ke Pekalongan
    const defaultLng = parseFloat(longitudeInput?.value) || 109.6784;

    // Inisialisasi peta
    map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

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