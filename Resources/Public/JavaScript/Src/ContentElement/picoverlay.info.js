/**
 * Controls the overlaying from the picoverlay info type content element.
 * Together with the related css the overlay is shown when js is disabled.
 */
(function () {
    function showOverlay () {
        this.closest('.pp-picoverlay').classList.add('ppc-overlay');
    }
    function hideOverlay () {
        this.closest('.pp-picoverlay').classList.remove('ppc-overlay');
    }
    const picoverlays = document.querySelectorAll('.pp-picoverlay.ppc-info');
    picoverlays.forEach((el) => {
        el.classList.add('ppc-init');
        el.addEventListener('mouseover', showOverlay);
        el.addEventListener('mouseout', hideOverlay);
        el.querySelector('.ppc-show').addEventListener('click', showOverlay);
        el.querySelector('.ppc-hide').addEventListener('click', hideOverlay);
    })
})();
