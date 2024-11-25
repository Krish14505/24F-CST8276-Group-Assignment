let map;
let directionsService;
let directionsRenderer;
let sourceAutoComplete;
let destinationAutoComplete;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 37.7749, lng: -122.4194 },
        zoom: 13,
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

    // Set user's current location as the source
    getUserLocation();
}

function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                // Convert coordinates to a formatted address
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: userLocation }, function (results, status) {
                    if (status === "OK" && results[0]) {
                        document.getElementById("source").value = results[0].formatted_address;
                    } else {
                        console.error("Geocoder failed due to: " + status);
                    }
                });
            },
            function (error) {
                console.error("Error getting location: ", error);
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
}

function calculateRoute() {
    const source = document.getElementById("source").value;
    const dest = document.getElementById("Destination").value;

    if (!source || !dest) {
        alert("Both source and destination are required.");
        return;
    }

    let request = {
        origin: source,
        destination: dest,
        travelMode: "DRIVING",
    };

    directionsService.route(request, function (result, status) {
        if (status === "OK") {
            directionsRenderer.setDirections(result);

            // Display route info
            displayRouteInfo(source, dest, result);

            // Automatically save the route to the backend
            const route = result.routes[0].legs[0];
            saveRouteToBackend({
                route_name: "Unnamed Route", // Can be changed dynamically if needed
                start_location: source,
                end_location: dest,
                total_distance: parseFloat(route.distance.text.replace(/[^\d.]/g, "")), // Remove "km" or "mi"
            });
        } else {
            console.error("Directions request failed due to " + status);
        }
    });
}


function saveRouteToBackend(routeData) {
    fetch("server/save_routes.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(routeData),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Route saved successfully!");
            } else {
                alert("Failed to save route: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error saving route:", error);
            alert("An error occurred while saving the route.");
        });
}


function displayRouteInfo(source, destination, result) {
    const route = result.routes[0].legs[0];

    // Set values in the popup
    document.getElementById("route-source").textContent = source;
    document.getElementById("route-destination").textContent = destination;
    document.getElementById("route-distance").textContent = route.distance.text;
    document.getElementById("route-duration").textContent = route.duration.text;

    document.getElementById("route-info").style.display = "block";
}
