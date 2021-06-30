/**
 * Controls the overlaying from the picoverlay info type content element.
 * Together with the related css the overlay is shown when js is disabled.
 */
(function ($) {
    function showOverlay () {
        $picoverlay = $(this).closest('.pp-picoverlay').addClass('ppc-overlay');
    }
    function hideOverlay () {
        $picoverlay = $(this).closest('.pp-picoverlay').removeClass('ppc-overlay');
    }
    let $picoverlay = $('.pp-picoverlay.ppc-info');
    // Set flag indicating js is allowed
    $picoverlay.addClass('ppc-init');
    // Attach hover event handlers
    $picoverlay.hover(showOverlay, hideOverlay);
    // Attach click event handlers
    $('.ppc-show', $picoverlay).on('click', showOverlay);
    $('.ppc-hide', $picoverlay).on('click', hideOverlay);
})(jQuery);
