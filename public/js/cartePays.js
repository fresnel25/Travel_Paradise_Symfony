document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('world-map').setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Données passées depuis le contrôleur
        const locations = LOCATIONS_DATA;

        locations.forEach(loc => {
            L.circleMarker([loc.lat, loc.lng], {
                color: 'blue',
                radius: 6,
                fillOpacity: 0.8
            }).addTo(map);
        });
    });