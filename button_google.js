// geoAPI Link is from google api

const http = new XMLHttpRequest()
let result = document.querySelector("#result")

document.querySelector("#share").addEventListener("click", () => {
    findMyCoordinates()
})

function findMyCoordinates(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
            (position) =>{
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                console.log("Latitude: ", latitude, "Longitude: ", longitude);
                
                const geoAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=AIzaSyDwrOSN1lzpjga-RY3zVGYs9mUWSWp6VJ4`;
                
                getAPI(geoAPI);
            },
            (err) => {
                alert(err.message);
            }
        )
    } else{
        alert("Geolocation unavailable in your current browser!");
    }
}

function getAPI(geoAPI){
    http.open("GET", geoAPI);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState === 4 && this.status === 200){
            const response = JSON.parse(this.responseText);
            //We may use a 'results' array with address components
            if (response.results && response.results.length > 0) {
                const addressComponents = response.results[0].address_components;
                const formattedAddress = response.results[0].formatted_address;
                //extract relevant address components
                let country = "";
                let city = "";
                let postalCode = "";

                for (const component of addressComponents) {
                    if (component.types.includes("country")) {
                        country = component.long_name;
                    } else if (component.types.includes("locality"))   
                    {               
                        city = component.long_name;
                    } else if (component.types.includes("postal_code"))   
                    {
                        postalCode = component.long_name;
                    }
                }
            //Display formatted address & extracted components
            result.innerHTML = `
            Formatted Address: ${formattedAddress}<br>
            Country: ${country}<br>
            City: ${city}<br>
            Postal Code: ${postalCode}
        `;

        //TODO : send data to our MySQL Database
        
        } else {
            result.innerHTML = "Location information not found.";
            
        }
    }
};
}
