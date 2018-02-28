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
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
        }
    }, function() {
        if($('button.navbar-toggle:hidden').length) {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
        }
    });

});
