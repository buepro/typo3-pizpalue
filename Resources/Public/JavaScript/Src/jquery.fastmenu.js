+function ($) {

    // DOM ready
    $(function () {
        $('.pp-fm-handle').click(function () {
            $(this).parent().toggleClass('pp-minimize');
        });
        $('.pp-fm-contenticon').click(function () {
            var id = '#' + $(this).attr('data-pp-fm-content');
            var $content = $(id);
            if ($content.hasClass('pp-show')) {
                $content.toggleClass('pp-show');
            } else {
                $content.siblings().removeClass('pp-show');
                $content.toggleClass('pp-show');
            }
        });
    });

} (jQuery);