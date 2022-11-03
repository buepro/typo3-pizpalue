/**
 * Controls the overlaying from the picoverlay bar type content element.
 * Together with the related css the overlay is shown when js is disabled.
 * It takes into account that images might be loaded on demand.
 */
(function () {
    const picoverlays = document.querySelectorAll('.pp-picoverlay.ppc-bar');
    // set css considering image isn't loaded yet (lazy loading)
    picoverlays.forEach((el) => el.classList.add('ppc-init'));
    picoverlays.forEach((el) => el.querySelector('.ppc-show').addEventListener('click', function () {
        const picoverlay = pp.parents(this, '.pp-picoverlay')[0];
        const text = picoverlay.querySelector('.pp-picoverlay-text');
        const height = picoverlay.querySelector('.pp-picoverlay-gallery').offsetHeight;
        const top = height - picoverlay.querySelector('.ppc-header').offsetHeight;
        // control overlaying with property `top`
        text.style.top = top + 'px';
        picoverlay.classList.add('ppc-overlay');
        // overlay
        text.querySelector('.ppc-show').style.display = 'none';
        setTimeout(function(){
            text.style.top = '0';
            picoverlay.classList.add('ppc-active');
        }, 10);
    }))
    picoverlays.forEach((el) => el.querySelector('.ppc-hide').addEventListener('click',function () {
        const picoverlay = pp.parents(this, '.pp-picoverlay')[0];
        const text = picoverlay.querySelector('.pp-picoverlay-text');
        const height = picoverlay.querySelector('.pp-picoverlay-gallery').offsetHeight;
        const top = height - picoverlay.querySelector('.ppc-header').offsetHeight;
        text.style.top = top + 'px';
        setTimeout(function(){
            text.querySelector('.ppc-show').style.display = 'block';
            picoverlay.classList.remove('ppc-active');
        }, 200);
    }))
})();
