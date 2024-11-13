//routes.js

let map;
let directionsService;
let directionsRenderer;
let sourceAutoComplete;
let destinationAutoComplete;

function initMap(){
    map = new google.maps.Map(document.getElementById("map"), {
        center: {lat: 37.7749, lng: -122.4194},
        zoom: 13
    });

    google.maps.event.addListener(map, "click", function(event) {
        this.setOptions({scrollwheel: true});
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    sourceAutoComplete = new google.maps.places.Autocomplete(
        document.getElementById("source")
    );

    destinationAutoComplete = new google.maps.places.Autocomplete(
        document.getElementById("Destination")
    );

    // Set the user's current location as the source
    getUserLocation();
}

function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            // Convert coordinates to a human-readable address
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: userLocation }, function(results, status) {
                if (status === "OK" && results[0]) {
                    document.getElementById("source").value = results[0].formatted_address;
                } else {
                    console.error("Geocoder failed due to: " + status);
                }
            });
        }, function(error) {
            console.error("Error getting location: ", error);
        });
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
}

function calculateRoute() {
    const source = document.getElementById("source").value;
    const dest = document.getElementById("Destination").value;

    let request = {
        origin: source,
        destination: dest,
        travelMode: 'DRIVING',
    };

    directionsService.route(request, function(result, status) {
        if (status === "OK") {
            directionsRenderer.setDirections(result);

            // Display route information
            displayRouteInfo(source, dest, result);
        } else {
            console.error("Directions request failed due to " + status);
        }
    });
}

function displayRouteInfo(source, destination, result) {
    const route = result.routes[0].legs[0];

    // Set values in the popup
    document.getElementById("route-source").textContent = source;
    document.getElementById("route-destination").textContent = destination;
    document.getElementById("route-distance").textContent = route.distance.text;
    document.getElementById("route-duration").textContent = route.duration.text;

    // Show the popup
    document.getElementById("route-info").style.display = "block";
}