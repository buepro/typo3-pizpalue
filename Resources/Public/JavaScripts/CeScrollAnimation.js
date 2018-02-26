/**
 * @see TS const plugin.bootstrap_package_contentelements.preload.images = 0
 *
 **/

 var debug = 1;

/**
 * extend the provided class
 *
 * @see bootstrap_package/../jquery.responsiveimages.js
 */
+function($) {

    /**
     * ensure images are loaded prior beeing visible in viewport
     */
    $.fn.responsiveimage.Constructor.DEFAULTS.threshold = 200;

}(jQuery);

+function($) {

    $('.frame-pp-scrollanimation1')
        .attr('data-aos','fade-up-right')
        .attr('data-aos-anchor-placement','center-bottom');

    $('.frame-pp-scrollanimation2')
        .attr('data-aos','fade-up-left')
        .attr('data-aos-anchor-placement','center-bottom');

    $('.frame-pp-scrollanimation3')
        .attr('data-aos','zoom-in')
        .attr('data-aos-anchor-placement','center-bottom');

	$(document).ready(function () {
        window.setTimeout(function(){
            AOS.init({
                easing: 'ease-in-out-sine',
            });
            //console.log('aos initialized');
        }, 500);
	});

	$(window).on('loaded.bk2k.responsiveimage',function() {
        window.setTimeout(function(){
            AOS.refresh();
            //console.log('aos refreshed');
        }, 5);
        //console.log('loaded.bk2k.responsiveimage');
	});

} (jQuery);
