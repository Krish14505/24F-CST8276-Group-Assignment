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
    </script>
</body>
</html>
