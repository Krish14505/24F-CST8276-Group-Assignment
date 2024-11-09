const http = new XMLHttpRequest()
let result = document.querySelector("#result")

document.querySelector("#share").addEventListener("click", () => {
    findMyCoordinates()
})

function findMyCoordinates(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition
        ((position) =>{
            const geoAPI = 'https://api-bdc.net/data/reverse-geocode?latitude=-34.93129&longitude=138.59669&localityLanguage=en&key=[AIzaSyA_d9OtADASjokYvll_eFL-MNTlcBWYBzI]'
            console.log(position.coords.latitude, 
                        position.coords.longitude)
                        getAPI(geoAPI)
        },
        (err) => {
            alert(err.message)
        })
    } else{
        alert("Geolocation unavailable in your current browser!")
    }
}

function getAPI(geoAPI){
    http.open("GET", geoAPI)
    http.send()
    http.onreadystatechange = function(){
        if(this.readyState === 4 && this.status === 200){
            result.innerHTML = this.responseText
        }
    }
}