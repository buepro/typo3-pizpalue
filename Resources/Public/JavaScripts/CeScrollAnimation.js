/**
 * @see TS const plugin.bootstrap_package_contentelements.preload.images = 0
 *
 **/


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


/**
 * initialize and update AOS
 *
 */
+function($) {

	$(window).on('loaded.bk2k.responsiveimage',function() {
        window.setTimeout(function(){
            AOS.refresh();
            //console.log('aos refreshed');
        }, 5);
        //console.log('loaded.bk2k.responsiveimage');
	});

    $(document).ready(function () {
        window.setTimeout(function(){
            if (typeof ppFeaturesScrollAnimationInitObject === 'object') {
                AOS.init(ppFeaturesScrollAnimationInitObject);
            } else {
                AOS.init({
                    easing: 'ease-in-out-sine',
                });
            }
            //console.log('aos initialized');
        }, 500);
    });

}(jQuery);
