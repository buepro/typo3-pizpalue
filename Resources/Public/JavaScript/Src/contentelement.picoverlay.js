/**
 * Controls the overlaying from the picoverlay content element.
 * Together with the related css the overlay is shown when js is disabled.
 * It takes into account that images might be loaded on demand.
 */
(function ($) {
    let $picoverlay = $('.pp-picoverlay');
    // set css considering image isn't loaded yet (lazy loading)
    $picoverlay.addClass('ppc-init');
    $('.ppc-show', $picoverlay).on('click', function () {
        let $picoverlay = $(this).parents('.pp-picoverlay');
        let $text = $('.pp-picoverlay-text', $picoverlay);
        let height = $('.pp-picoverlay-gallery', $picoverlay).outerHeight();
        let top = height - $('.ppc-header', $picoverlay).outerHeight();
        // control overlaying with property `top`
        $text.css('top', top + 'px');
        $picoverlay.addClass('ppc-overlay');
        // overlay
        $('.ppc-show', $text).css('display', 'none');
        setTimeout(function(){
            $text.css('top', 0);
        }, 10);
    });
    $('.ppc-hide', $picoverlay).on('click', function () {
        let $picoverlay = $(this).parents('.pp-picoverlay');
        let $text = $('.pp-picoverlay-text', $picoverlay);
        let height = $('.pp-picoverlay-gallery', $picoverlay).outerHeight();
        let top = height - $('.ppc-header', $picoverlay).outerHeight();
        $text.css('top', top + 'px');
        setTimeout(function(){
            $('.ppc-show', $text).css('display', 'block');
        }, 200);
    });
})(jQuery);
