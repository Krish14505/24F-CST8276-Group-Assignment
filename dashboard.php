<?php include('header.php'); ?>
<?php include('server/user_auth.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="header"></div> 
    <div class="content">
        <h2>Welcome to Your Dashboard</h2>
        <h3>Your Saved Locations</h3>
        <br>
        <div id="locations"></div> <!-- This is where the saved locations will be displayed -->

        <!-- Saved Routes Section -->
        <h3>Your Saved Routes</h3>
        <br>
        <div id="routes"></div> <!-- Saved routes will be displayed here -->

    </div>
    <?php include('footer.php'); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('server/get_locations.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const locationsDiv = document.getElementById("locations");
                    
                    if (data.success === false) {
                        locationsDiv.innerHTML = `<p>${data.message}</p>`;
                    } else if (Array.isArray(data.locations) && data.locations.length > 0) {
                        // Render locations dynamically with a two-column layout
                        locationsDiv.innerHTML = data.locations.map(loc => `
                            <div class="location-card" id="location-${loc.location_id}">
                                <div class="location-info">
                                    <p><strong>Address:</strong> ${loc.formatted_address}</p>
                                    <p><strong>Saved At:</strong> ${loc.timestamp}</p>
                                </div>
                                <button class="del-btn" onclick="deleteLocation(${loc.location_id})">Delete</button>
                            </div>
                        `).join('');
                    } else {
                        locationsDiv.innerHTML = "<p>No saved locations found.</p>";
                    }
                })
                .catch(error => {
                    console.error('Error fetching locations:', error);
                    document.getElementById("locations").innerHTML = "<p>Failed to load locations.</p>";
                });


            // Fetch and display saved routes
            fetch('server/get_routes.php')
                .then(response => response.json())
                .then(data => {
                    const routesDiv = document.getElementById("routes");
                    
                    if (data.success === false) {
                        routesDiv.innerHTML = `<p>${data.message}</p>`;
                    } else if (Array.isArray(data.routes) && data.routes.length > 0) {
                        routesDiv.innerHTML = data.routes.map(route => `
                            <div class="route-card" id="route-${route.route_id}">
                                <div class="route-info">
                                    <p><strong>Route Name:</strong> ${route.route_name}</p>
                                    <p><strong>From:</strong> ${route.start_location}</p>
                                    <p><strong>To:</strong> ${route.end_location}</p>
                                    <p><strong>Distance:</strong> ${route.total_distance} km</p>
                                </div>
                                <button class="del-btn" onclick="deleteRoute(${route.route_id})">Delete</button>
                            </div>
                        `).join('');
                    } else {
                        routesDiv.innerHTML = "<p>No saved routes found.</p>";
                    }
                })
                .catch(error => {
                    console.error('Error fetching routes:', error);
                    document.getElementById("routes").innerHTML = "<p>Failed to load routes.</p>";
                });
        });

        // Function to handle location deletion
        function deleteLocation(locationId) {
            if (confirm("Are you sure you want to delete this location?")) {
                fetch('server/delete_location.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ location_id: locationId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`location-${locationId}`).remove();
                    } else {
                        alert("Failed to delete location: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting location:', error);
                    alert("An error occurred while deleting the location.");
                });
            }
        }

        // Function to delete a route
        function deleteRoute(routeId) {
            if (confirm("Are you sure you want to delete this route?")) {
                fetch('server/delete_route.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ route_id: routeId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`route-${routeId}`).remove();
                    } else {
                        alert("Failed to delete route: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting route:', error);
                    alert("An error occurred while deleting the route.");
                });
            }
        }
    </script>
</body>
</html>
