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
    +function () {
        if (ppFrameRevealFooter) {
            $('#content').addClass('pp-reveal-footer')
                .css('margin-bottom',$('footer').height());
            $('footer').addClass('pp-reveal-footer');
        }
    }();
    $(window).resize(function() {
        $('#content').css('margin-bottom',$('footer').height());
    });

});
