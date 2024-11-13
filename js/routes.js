//routes.js

let map;
    let directionsService;
    let directionsRenderer;
    let sourceAutoComplete;
    let destinationAutoComplete;

    function initMap(){
        map = new google.maps.Map(document.getElementById("map"), {
            center: {lat: 37.7749, lng: -122.4194},
            zoom: 13
        });

        google.maps.event.addListener(map, "click", function(event) {
            this.setOptions({scrollwheel: true});
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);

        sourceAutoComplete = new google.maps.places.Autocomplete(
            document.getElementById("source")
        );

        destinationAutoComplete = new google.maps.places.Autocomplete(
            document.getElementById("Destination")
        );
    }

    function calculateRoute() {
    var source = document.getElementById("source").value;
    var dest = document.getElementById("Destination").value;

    let request = {
        origin: source,
        destination: dest,
        travelMode: 'DRIVING',
    };

    directionsService.route(request, function(result, status) {
        if (status === "OK") {
            directionsRenderer.setDirections(result);
        } else {
            console.error("Directions request failed due to " + status);
        }
    });
}