<!DOCTYPE html>
<html>
<head>
    <title>Suivi des Colis</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>Position des Colis</h2>
    <div id="map"></div>

    <script>
        async function initMap() {
            // Récupération des coordonnées depuis ton API Laravel
            const response = await fetch('/vehicles/1/position');
            const data = await response.json();

            const position = { lat: parseFloat(data.latitude), lng: parseFloat(data.longitude) };

            // Initialisation de la carte
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: position,
            });

            // Ajout du marqueur
            new google.maps.Marker({
                position: position,
                map: map,
                title: "Véhicule " + data.plate_number,
            });
        }

        initMap();
    </script>
</body>
</html>
