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
let currentLatitude = null;
let currentLongitude = null;
let isSharing = false;

// Google Maps variables
let map;
let circle;
let directionsService;
let directionsRenderer;
let routePath = []; // Store the route path

// Add event listeners to the buttons share my location, stop sharing, and save location
document.querySelector("#share").addEventListener("click", startLocationUpdates);
document.querySelector("#stop").addEventListener("click", stopLocationUpdates);
const saveLocationButton = document.querySelector("#save-location");
if (saveLocationButton) {
    saveLocationButton.addEventListener("click", saveCurrentLocation);
}

const spinner = document.querySelector("#spinner");

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

/**
 * Function to start location updates
 */
function startLocationUpdates() {
    if (!intervalId) {
        findMyCoordinates();
        intervalId = setInterval(findMyCoordinates, 15000);
        document.querySelector("#stop").disabled = false;
        document.querySelector("#share").disabled = true;
        saveLocationButton.style.display = "inline-block"; // Show Save button
        spinner.style.display = "block";
        isSharing = true;
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
        saveLocationButton.style.display = "none"; // Hide Save button
        spinner.style.display = "none";
        isSharing = false;
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
                currentLatitude = position.coords.latitude;
                currentLongitude = position.coords.longitude;
                console.log("Latitude:", currentLatitude, "Longitude:", currentLongitude);

                const geoAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${currentLatitude},${currentLongitude}&key=AIzaSyCjk8ThaZ9tgH1FPGAch_JCECbysZS3_So`;

                updateMap(currentLatitude, currentLongitude);
                getAPI(geoAPI, currentLatitude, currentLongitude);
            },
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
 * Function to update the map with the current location
 */
function updateMap(latitude, longitude) {
    const position = { lat: latitude, lng: longitude };
    routePath.push(position);
    map.setCenter(position);

    if (circle) {
        circle.setMap(null);
    }

    circle = new google.maps.Circle({
        strokeColor: "#031546",
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
 * Function to save the current location to the database
 */
async function saveCurrentLocation() {
    if (currentLatitude !== null && currentLongitude !== null && isSharing) {
        // Fetch the user ID from the session
        const sessionResponse = await fetch("../server/get_session.php");
        const sessionData = await sessionResponse.json();

        if (!sessionData.user_id) {
            alert("User not logged in. Please sign in first.");
            return;
        }

        // Include address information
        const locationData = {
            user_id: sessionData.user_id,
            latitude: currentLatitude,
            longitude: currentLongitude,
            formatted_address: currentLocationData?.formatted_address || "Unknown",
            city: currentLocationData?.city || "",
            country: currentLocationData?.country || "",
            postal_code: currentLocationData?.postal_code || ""
        };

        try {
            const response = await fetch("../server/save_location.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(locationData)
            });

            const result = await response.json();
            if (result.success) {
                alert("Location saved successfully!");
            } else {
                alert("Failed to save location: " + result.message);
            }
        } catch (error) {
            console.error("Error saving location:", error);
            alert("Error saving location.");
        }
    } else {
        alert("No location data to save!");
    }
}


/**
 * Fetching the address using Google API and saving location data
 */
function getAPI(geoAPI, latitude, longitude) {
    console.log("Fetching address from:", geoAPI);
    http.open("GET", geoAPI);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.responseText);
            const newAddress = response.results[0]?.formatted_address || "Address not found";
            const locationData = response.results[0]?.address_components || [];
            const country = locationData.find(item => item.types.includes("country"))?.long_name || "";
            const city = locationData.find(item => item.types.includes("locality"))?.long_name || "";
            const postal_code = locationData.find(item => item.types.includes("postal_code"))?.long_name || "";

            // Update global currentLocationData object
            currentLocationData = {
                latitude,
                longitude,
                formatted_address: newAddress,
                city,
                country,
                postal_code
            };

            console.log("Fetched Address:", currentLocationData);
        }
    };
}


// Update the current time every second
setInterval(updateCurrentTime, 1000);
document.addEventListener("DOMContentLoaded", initMap);
