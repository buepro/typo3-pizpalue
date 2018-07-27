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
        $('.pp-parentheight,.pp-parent-height').height(0);
        $('.pp-parentheight,.pp-parent-height').each(function () {
            $(this).height($(this).parent().height());
        });
    }
    $(window).on('load,resize',sizeToParentHeight);
    $('img').on('load',sizeToParentHeight);


    /**
     * size the element height to the embedding row height (e.g. a content element in a column will be as high as
     * the row it belongs to). the class pp-row-height is used for that purpose.
     *
     * currently just one nesting level is supported.
     *
     */
    function sizeToRowHeight() {
        // get rows
        var $rows = $('.pp-row-height').closest('.row');
        $rows.each(function() {
            var maxHeight = 0;

            // reset heights
            var $elements = $('.pp-row-height',$(this)).css('height','auto');

            // get max height
            $elements.each(function() {
                var $this = $(this);
                if ($this.height() > maxHeight) maxHeight = $this.height();
            });

            // set max height
            $elements.height(maxHeight);
        });
    }
    $(window).on('load,resize',sizeToRowHeight);
    $('img').on('load',sizeToRowHeight);


    /**
     * harmonize children heights for a column element in a row. the result is that all column elements have the same
     * appearance (each child element has the same height). an example are column elements consisting of a header,
     * an image and a text. some elements might have the header spanning more than one line. with this function all
     * header elements in a row would have the same height.
     *
     */
    function sizeChildrenToRowHeight() {
        // get rows
        var $rows = $('.pp-row-child-height').closest('.row');
        $rows.each(function() {
            var $elements = $('.pp-row-child-height',$(this));
            var childCount = $elements.first().children().length;
            var maxHeights = [];
            maxHeights.length = childCount;
            maxHeights.fill(0);

            // reset height
            $elements.children().css('height','auto');

            // get max heights
            $elements.each(function() {
                $(this).children().each(function(iChild) {
                    var $this = $(this);
                    if($this.height() > maxHeights[iChild]) maxHeights[iChild] = $this.height();
                });
            });

            // set max heights
            $elements.each(function(){
                $(this).children().each(function(iChild) {
                    $(this).height(maxHeights[iChild]);
                });
            });
        });
    }
    $(window).on('load,resize',sizeChildrenToRowHeight);
    $('img').on('load',sizeChildrenToRowHeight);


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
