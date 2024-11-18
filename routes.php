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
</body>
</html>

