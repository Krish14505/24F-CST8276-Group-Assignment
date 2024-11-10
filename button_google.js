// Using Google API for Geolocation
const http = new XMLHttpRequest();
let result = document.querySelector("#result");
let currentTimeDisplay = document.querySelector("#currentTime");

// Variables to store state and interval
let previousAddress = "";
let fetchCount = 0;
let intervalId = null;

// Add event listeners to the buttons
document.querySelector("#share").addEventListener("click", startLocationUpdates);
document.querySelector("#stop").addEventListener("click", stopLocationUpdates);

// Function to start location updates
function startLocationUpdates() {
    if (!intervalId) { // Prevent multiple intervals
        findMyCoordinates(); // Run once immediately
        intervalId = setInterval(findMyCoordinates, 15000); // Every 15 seconds (1000 for every second)
        document.querySelector("#stop").disabled = false;
        document.querySelector("#share").disabled = true;
    }
}

// Function to stop location updates
function stopLocationUpdates() {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
        console.log("Location updates stopped.");
        document.querySelector("#stop").disabled = true;
        document.querySelector("#share").disabled = false;
    }
}

// Function to update the current time display every second
function updateCurrentTime() {
    const currentTime = new Date().toLocaleTimeString();
    currentTimeDisplay.textContent = currentTime;
}

// Function to get the user's current location
function findMyCoordinates() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                console.log("Latitude:", latitude, "Longitude:", longitude);

                const geoAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=AIzaSyA_d9OtADASjokYvll_eFL-MNTlcBWYBzI`;
                
                getAPI(geoAPI);
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

// Function to fetch address using Google Maps API
function getAPI(geoAPI) {
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
        }
    };
}

// Start updating the current time as soon as the page loads
setInterval(updateCurrentTime, 1000); // Update time every second
