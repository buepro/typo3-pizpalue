/**
 * on document ready
 */
$(function () {

    /**
     * select first tab if none is selected
     */
    (function(){
        $('.tab-v1').each(function(){
            var $this = $(this);
            var hasActiveTab = $('li.active',$this);
            if(!$(hasActiveTab).length) {
                $('li a[data-toggle="tab"]',$this).first().tab('show');
            }
        });
    })();

    /**
     * select a tab based on the url: .../#tab2-363 (tab index - content uid)
     *
     */
    (function () {
        var url = window.location.href;
        var urlParts = decodeURI(url).split('#');
        if (urlParts.length == 2) {
            var selTab = urlParts[1];
            var tabParts = selTab.split('-');
            if (tabParts.length == 2 && tabParts[0].indexOf("tab") != -1) {
                var iTab = tabParts[0].slice(3) - 1;
                var cElementId = "#c" + tabParts[1];
                var Tab = $(cElementId + " .nav-tabs a").get(iTab);
                $(Tab).tab("show");
            }
        }
    })();

    /**
     * set image column with for a image row
     *
     */
    (function(){
        function setEqualImageColumnHeight() {
            var rows = $('.pp-ce-equalimage .image-row');
            $(rows).each(function (i) {
                var cols = $('.image-column', this);
                var maxHeight = 0;
                $(cols).css('height', 'auto');
                cols.each(function () {
                    if ($(this).height() > maxHeight) maxHeight = $(this).height();
                });
                if (maxHeight > 10) {
                    cols.each(function () {
                        $(this).height(maxHeight);
                    });
                }
                ;
            });
        };

        var rows = $('.pp-ce-equalimage .image-row');
        if ($(rows).length) {
            $(document).ready(function () {
                window.setTimeout(setEqualImageColumnHeight, 10);
                window.setTimeout(setEqualImageColumnHeight, 100);
                setEqualImageColumnHeight();
            });
            $(window).on('loaded.bk2k.responsiveimage', function () {
                setEqualImageColumnHeight();
            });
            $(window).on('resize.bk2k.responsiveimage', function () {
                setEqualImageColumnHeight();
            });
        };
    })();

});
