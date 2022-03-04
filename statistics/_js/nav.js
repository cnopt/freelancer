function hideNav() {
    // used to hide the side navigation menu, when the user clicks the X
    document.getElementById("main-page-nav").style.transform = "translateX(-250px)";
}
function showNav() {
    // used to show the side nav menu, when the user clicks the 'burger' button
    document.getElementById("main-page-nav").style.transform = "translateX(0)";
}