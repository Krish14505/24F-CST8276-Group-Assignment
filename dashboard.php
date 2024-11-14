<!-- dashboard.php -->
<?php include('header.php'); ?>
<?php include('server/user_auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="header"></div> 
    <div class="content">
        <h2>Welcome to Your Dashboard</h2>
        
        <h3>Your Saved Locations</h3>
        <div id="locations"></div>
    </div>
    <?php include('footer.php'); ?>


    <script>
        // Fetch saved locations when the page loads
        document.addEventListener("DOMContentLoaded", function() {
            fetch('server/get_locations.php')
                .then(response => response.json())
                .then(data => {
                    const locationsDiv = document.getElementById("locations");
                    
                    if (data.success === false) {
                        locationsDiv.innerHTML = `<p>${data.message}</p>`;
                    } else {
                        if (data.length > 0) {
                            locationsDiv.innerHTML = data.map(loc => `
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
                .catch(error => {
                    console.error('Error fetching locations:', error);
                    document.getElementById("locations").innerHTML = "<p>Failed to load locations.</p>";
                });
        });
    </script>
    
</body>
</html>
