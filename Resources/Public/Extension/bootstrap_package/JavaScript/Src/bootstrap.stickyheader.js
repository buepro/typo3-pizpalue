window.addEventListener('DOMContentLoaded', function () {

    var stickyheader = document.querySelectorAll(".navbar-fixed-top");
    if (stickyheader.length >= 1) {
        function animateHeader() {
            // Toggle class with y-hysteresis
            if (window.scrollY > 240) {
                stickyheader[0].classList.add("navbar-transition");
            }
            if (window.scrollY < 100) {
                stickyheader[0].classList.remove("navbar-transition");
            }
        }
        ['scroll', 'resize', 'DOMContentLoaded'].forEach(function (e) {
            window.addEventListener(e, animateHeader);
        });
        animateHeader();
    }

});
