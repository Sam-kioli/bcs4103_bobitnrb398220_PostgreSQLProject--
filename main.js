window.onscroll = function () { myFunction() };

var navbar = document.getElementById("navigation");
var logo = document.getElementById("logo");
var sticky = navbar.offsetTop;

function myFunction() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky");
        logo.classList.remove("hidden")
    } else {
        navbar.classList.remove("sticky");
        logo.classList.add("hidden")
    }
}