/**
 * return value from a key (name) in the url
 *
 * @see davidwalsh.name/query-string-javascript
 */
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};


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
            $(this).addClass('open');
        }
    }, function() {
        if($('button.navbar-toggle:hidden').length) {
            $(this).removeClass('open');
        }
    });

    /**
     * cookie consent adjustments
     *
     */
    +function() {
        if (!$('#cookieconsent').length) return;
        var $originalHeaderMargin = $('.body-bg > header').css('margin-top');
        var $originalFooterMargin = $('footer').css('margin-bottom');
        var $ccTop = $('#cookieconsent .cc-banner.cc-top');
        var $ccBottom = $('#cookieconsent .cc-banner.cc-bottom');
        if (!$('#cookieconsent .cc-invisible').length && !$('#cookieconsent .cc-static').length) {
            if ($ccTop.length) {
                $('.body-bg > header').css('margin-top',$ccTop.outerHeight(true));
            }
            if ($ccBottom.length) {
                $('footer').css('margin-bottom',$ccBottom.outerHeight(true));
            }
        }

        window.addEventListener('pizpalue.cookie.popupclose',function(){
            if ($ccTop.length) {
                $('.body-bg > header')
                    .css('transition', 'margin-top 0.5s 0.5s')
                    .css('margin-top',$originalHeaderMargin);
            }
            if ($ccBottom.length) {
                $('footer')
                    .css('transition', 'margin-bottom 0.5s 0.5s')
                    .css('margin-bottom',$originalFooterMargin);
            }
        });
    }();

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
                var footerHeight = $('footer').outerHeight(true);
                var contentHeight = $(window).outerHeight(true);
                if($('.navbar-fixed-top').length) contentHeight -= $('.navbar-fixed-top').outerHeight(true);
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

    /**
     * dataprotection label
     *
     */
    +function() {
        var multiCheckBox = $('#idGeneralContactForm-idDataprotectionMultiCheckbox');
        if (multiCheckBox.length > 0) {
            var html = $('.pp-label-dataprotection p').html();
            multiCheckBox.find('label > span').html(html);
        };
    }();

});
