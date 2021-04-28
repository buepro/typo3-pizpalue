(function ($) {
    $(function () {

        if ( $( "#is-bookmarked" ).length ) {
            // add a class to all bookmark links on the page because somewhere is a #is-bookmarked flag in html
            $('.bookmark-this-page').addClass('is-bookmarked');
        }

        // doing it this way:
        // $('.bookmark-ajax-submit').on('click', function (event) {
        // would not work for initially hidden elements

        $('.bookmark-pages').on('click', '.bookmark-ajax-submit', function (event) {
            event.preventDefault();
            var $submitButton = $(this);
            var uri = $submitButton.data('ajaxuri');
            $.ajax(
                uri,
                {
                    'type': 'post'
                }
            ).done(function (result) {
                $('#bookmarks-list').html(result.list);

                if (result.isBookmarked) {
                    $('.bookmark-this-page').addClass('is-bookmarked');
                } else {
                    $('.bookmark-this-page').removeClass('is-bookmarked');
                }
            });
        });

    });
})(jQuery);
