<?php include('auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Welcome to Your Dashboard</h2>
    <button onclick="window.location.href='location.php'">Save Current Location</button>
    
    <h3>Your Saved Locations</h3>
    <div id="locations"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('get_locations.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success === false) {
                        alert(data.message);
                    } else {
                        const locationsDiv = document.getElementById("locations");
                        if (data.locations && data.locations.length > 0) {
                            locationsDiv.innerHTML = data.locations.map(loc => `
                                <div>
                                    <p><strong>Address:</strong> ${loc.formatted_address}</p>
                                    <p><strong>City:</strong> ${loc.city}, <strong>Country:</strong> ${loc.country}</p>
                                    <p><strong>Coordinates:</strong> (${loc.latitude}, ${loc.longitude})</p>
                                    <p><strong>Saved At:</strong> ${loc.timestamp}</p>
                                </div>
                                <hr>
                            `).join('');
                        } else {
                            locationsDiv.innerHTML = "<p>No saved locations found.</p>";
                        }
                    }
                })
                .catch(error => console.error('Error fetching locations:', error));
        });
    </script>
</body>
</html>
