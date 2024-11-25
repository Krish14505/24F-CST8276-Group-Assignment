<!-- routes.php-->

<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/route.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjk8ThaZ9tgH1FPGAch_JCECbysZS3_So&libraries=places"></script>
</head>

<body id="routes-page" onload="initMap()">

    <div class="content">
            <!-- Main Content -->
        <h1 class="text-center">Direction Route Finder</h1>
        <br><br>

        <div class="container">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Source Location" id="source">
                <input type="text" class="form-control" placeholder="Destination Location" id="Destination">
                <button id="buttons" onclick="calculateRoute()">Get Directions</button>
            </div>
            <div class="container">
            <h1>Save Your Route</h1>
              <form id="routeForm">
                <input type="text" id="route_name" class="form-control" placeholder="Route Name (Optional)">
                <button type="button" class="btn btn-secondary" id="saveRouteButton" onclick="saveRoute()">Save Route</button>
              </form>
             <div id="routeMessage" class="alert" style="display: none;"></div>
    </div>


<script>
    async function saveRoute() {
        const routeData = {
            route_name: document.getElementById('route_name').value,
            total_distance: document.getElementById('total_distance').value,
            start_location_id: document.getElementById('start_location_id').value,
            end_location_id: document.getElementById('end_location_id').value,
        };

        try {
            const response = await fetch('server/save_routes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(routeData),
            });
            const result = await response.json();
            const messageBox = document.getElementById('routeMessage');
            if (result.success) {
                messageBox.className = 'alert alert-success';
                messageBox.innerText = result.message;
            } else {
                messageBox.className = 'alert alert-danger';
                messageBox.innerText = result.message;
            }
            messageBox.style.display = 'block';
        } catch (error) {
            console.error('Error saving route:', error);
        }
    }
</script>

            <div id="map"></div>
            <!-- Route Information Popup -->
            <div id="route-info">
                <h3>Route Details</h3>
                <p><strong>Source:</strong> <span id="route-source"></span></p>
                <p><strong>Destination:</strong> <span id="route-destination"></span></p>
                <p><strong>Distance:</strong> <span id="route-distance"></span></p>
                <p><strong>Duration:</strong> <span id="route-duration"></span></p>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script src="js/routes.js"></script>
    

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const populateDropdown = async (dropdownId) => {
        try {
            const response = await fetch('server/get_locations.php');
            const data = await response.json();
            if (data.success) {
                const dropdown = document.getElementById(dropdownId);
                data.locations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.location_id;
                    option.textContent = location.formatted_address; // Use formatted_address for display
                    dropdown.appendChild(option);
                });
            } else {
                console.error(data.message);
            }
        } catch (error) {
            console.error('Error fetching locations:', error);
        }
    };

    await populateDropdown('start_location_id');
    await populateDropdown('end_location_id');
});
</script>
</body>
</html>

