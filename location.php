<!-- location.php -->

<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geolocation API Assignment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Google Maps API for Geolocation and Map rendering -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjk8ThaZ9tgH1FPGAch_JCECbysZS3_So&libraries=places&callback=initMap&t=${new Date().getTime()}" async defer></script>
</head>
<body id="location-page">

    <div class="content">
        <!-- Main Content -->
        <h1 class="text-center">Google API Location Sharing</h1>
        <h3>Current Time: <span id="currentTime"></span></h3>
        
        <div id="buttons" class="text-center">
            <!-- Buttons for location sharing and stopping -->
            <button id="share">Share My Location</button>
            <button id="stop" disabled>Stop Sharing</button>
            <button id="save-location" style="display: none;">Save Current Location</button>  <!-- This button is displayed once location sharing starts -->
        </div>

        <div id="spinner" class="text-center" style="display: none;">
            <!-- Spinner to show when location is being fetched -->
            <div class="spinner-border text-info" role="status">
                <span class="visually-hidden">Sharing..</span>
            </div>
        </div>

        <!-- Map container for displaying the user's location -->
        <div id="map"></div>
        
        <div class="result-container bg-light p-3">
            <div class="result-title">Location History</div>
            <pre id="result"></pre>
        </div>
        
    </div>

    <?php include('footer.php'); ?>
    <script src="js/button_google.js"></script>
</body>
</html>
