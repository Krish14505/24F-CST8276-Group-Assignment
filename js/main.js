// main.js
document.addEventListener("DOMContentLoaded", function() {
    // Load the header
    fetch('header.html')
        .then(response => response.text())
        .then(data => document.getElementById('header').innerHTML = data);

    // Load the footer
    fetch('footer.html')
        .then(response => response.text())
        .then(data => document.getElementById('footer').innerHTML = data);
});
