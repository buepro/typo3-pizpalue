/**
 * Remove empty link targets by assigning the anchor id to the following sibling or to one of its descendents.
 */
(function () {
    $('.pp-link-target').each(function () {
        let $a = $(this);
        let id = $a.attr('id');
        let $sibling = $a.next();
        if ($sibling.is('a') && $sibling.text() == "") {
            // The sibling was a link target too (the one for `_LOCALIZED_UID`)
            $sibling = $sibling.next();
        }
        if (!$sibling.attr('id')) {
            $sibling.attr('id', id);
            $a.remove();
            return;
        }
        // The sibling had an id assigned to. Let's try to assign the id to a descendent.
        let $children = $(':not([id]), [id=""]', $sibling);
        if ($children.length) {
            $children.first().attr('id', id);
            $a.remove();
        }
    });
})();
