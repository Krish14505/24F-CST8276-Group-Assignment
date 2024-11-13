//button_google.js
//This code is developed to use Google Geolocation API and retrieve user geolocation
//AUTHOR: Paulo Ricardo Gomes Granjeiro - 041118057, and Kyla Pineda
//Collaborators: Craig, Krish, Leonardo, Yazid

// Using Google API for Geolocation
const http = new XMLHttpRequest();
let result = document.querySelector("#result");
let currentTimeDisplay = document.querySelector("#currentTime");

// Variables to store state and interval
let previousAddress = "";
let fetchCount = 0;
let intervalId = null;

// Google Maps variables
let map;
let circle;
let directionsService;
let directionsRenderer;
let routePath = []; // Store the route path

// Add event listeners to the buttons share my location and stop sharing
document.querySelector("#share").addEventListener("click", startLocationUpdates);
document.querySelector("#stop").addEventListener("click", stopLocationUpdates);

/**
 * Function to initialize the Google Map
 */
function initMap() {
    if (map) {
        console.log("Map already initialized");
        return;
    }
    console.log("Initializing Map...");
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 0, lng: 0 },
        zoom: 15,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true
    });

    console.log("Map initialized successfully");
}


const spinner = document.querySelector("#spinner");

/**
 * Function to start location updates
 */
function startLocationUpdates() {
    if (!intervalId) {
        findMyCoordinates();
        intervalId = setInterval(findMyCoordinates, 15000);
        document.querySelector("#stop").disabled = false;
        document.querySelector("#share").disabled = true;

        // Show spinner
        spinner.style.display = "block";
    }
}

/**
 * Function to stop location updates
 */
function stopLocationUpdates() {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
        document.querySelector("#stop").disabled = true;
        document.querySelector("#share").disabled = false;

        // Hide spinner
        spinner.style.display = "none";
    }
}

/**
 * Function to update the current time display every second
 */
function updateCurrentTime() {
    const currentTime = new Date().toLocaleTimeString();
    currentTimeDisplay.textContent = currentTime;
}

/**
 * Function to get the user's current location
 */
function findMyCoordinates() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                console.log("Latitude:", latitude, "Longitude:", longitude);

                const geoAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=AIzaSyCjk8ThaZ9tgH1FPGAch_JCECbysZS3_So`; //google string to use geo APIs and also the key

                updateMap(latitude, longitude); // Update the map with the new coordinates
                getAPI(geoAPI, latitude, longitude);
            },
            // Error if findMyCoordinates fails
            (err) => {
                console.error("Error getting location:", err.message);
                alert("Error getting location: " + err.message);
            }
        );
    } else {
        alert("Geolocation unavailable in your current browser!");
    }
}

/**
 * Function to update the map with the current location and draw the route
 */
function updateMap(latitude, longitude) {
    const position = { lat: latitude, lng: longitude };

    // Add new location to route path
    routePath.push(position);
    map.setCenter(position);

    if (circle) {
        circle.setMap(null);
    }

    // Blue circle to show user's current location
    circle = new google.maps.Circle({
        strokeColor: "#031546", //border
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#031546", 
        fillOpacity: 0.8,
        map: map,
        center: position,
        radius: 15
    });

    if (routePath.length > 1) {
        drawRoute();
    }
}


/**
 * Function to draw the route on the map using DirectionsService
 */
function drawRoute() {
    const waypoints = routePath.slice(1, -1).map(location => ({
        location,
        stopover: false
    }));

    const origin = routePath[0];
    const destination = routePath[routePath.length - 1];

    directionsService.route(
        {
            origin,
            destination,
            waypoints,
            travelMode: google.maps.TravelMode.DRIVING
        },
        (response, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
            } else {
                console.error("Directions request failed due to " + status);
            }
        }
    );
}

/**
 * Function to fetch address using Google Maps API
 * @param {*} geoAPI 
 * @param {*} latitude 
 * @param {*} longitude 
 */
function getAPI(geoAPI, latitude, longitude) {
    console.log("Fetching address from:", geoAPI);
    http.open("GET", geoAPI);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.responseText);
            const newAddress = response.results[0]?.formatted_address || "Address not found";

            fetchCount++;
            const currentTime = new Date().toLocaleTimeString();

            if (newAddress) {
                console.log(`Fetch #${fetchCount} at ${currentTime}: ${newAddress}`);
                result.innerHTML += `\n${fetchCount}: ${newAddress} (Fetched at: ${currentTime})\n`;
                previousAddress = newAddress;
            } else {
                result.innerHTML += `\n${fetchCount}: Address not found (Fetched at: ${currentTime})\n`;
            }

            // ADDED BY PAULO
            const locationData = response.results[0]?.address_components || [];
            const country = locationData.find(item => item.types.includes("country"))?.long_name || "";
            const city = locationData.find(item => item.types.includes("locality"))?.long_name || "";
            const postal_code = locationData.find(item => item.types.includes("postal_code"))?.long_name || "";

            // Send data to PHP (server)
            sendLocationToServer({
                user_id: 1,
                latitude,
                longitude,
                country,
                city,
                postal_code,
                formatted_address: newAddress
            });
        }
    };
}

/**
 * Function to send data to the server (save_location) //ADDED BY PAULO
 * @param {*} data 
 */
function sendLocationToServer(data) {
    fetch("server/save_location.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server response:", data.message);
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

// Update time as soon as page loads
setInterval(updateCurrentTime, 1000); // Every second


document.addEventListener("DOMContentLoaded", function () {
    initMap();
});
