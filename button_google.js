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
                
                const geoAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=AIzaSyA_d9OtADASjokYvll_eFL-MNTlcBWYBzI`;
                
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
            result.innerHTML = this.responseText;
        }
    }
}
