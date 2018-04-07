/**
 * open the element from collapsable elements (e.g. accordion elements)
 * if the css style .csc-space-after-1 is found within .collapse
 */
$(function () {
    var el = $('.collapse .csc-space-after-1').closest('.collapse')
    if (el.length) {
        el.collapse('show');
    }
});

$(document).ready(function () {

    /**
     * size elements using the class pp-parentheight to have the same height
     * as its parent element
     *
     */
    function sizeToParentHeight() {
        $('.pp-parentheight').height(0);
        $('.pp-parentheight').each(function () {
            $(this).height($(this).parent().height());
        });
    };
    sizeToParentHeight();

    $(window).resize(sizeToParentHeight);


    /**
     * open dropdown menu on hover
     *
     */
    $('ul.navbar-main li.dropdown').hover(function() {
        if($('button.navbar-toggle:hidden').length) {
            $(this).toggleClass('open');
        }
    }, function() {
        if($('button.navbar-toggle:hidden').length) {
            $(this).toggleClass('open');
        }
    });

    /**
     * reveal footer
     *
     * js is used because the bottom margin from the content container
     * needs to be calculated (depends on screen width). in case js
     * is not supported by the client the footer is shown normally.
     *
     */
    +function() {
        var originalMargin = $('#content').css('margin-bottom');
        function revealFooter () {
            if (ppFrameRevealFooter) {
                var footerHeight = $('footer').height();
                var contentHeight = $(window).height();
                if($('.navbar-fixed-top').length) contentHeight -= $('.navbar-fixed-top').height();
                if (footerHeight < contentHeight) {
                    $('#content').addClass('pp-reveal-footer')
                        .css('margin-bottom',footerHeight);
                    $('footer').addClass('pp-reveal-footer');
                } else {
                    $('#content').removeClass('pp-reveal-footer')
                        .css('margin-bottom',originalMargin);
                    $('footer').removeClass('pp-reveal-footer');
                }
            }
        };
        revealFooter();
        $(window).resize(function() {
            revealFooter();
        });
    }();

});
