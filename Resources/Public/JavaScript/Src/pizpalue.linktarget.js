/**
 * Remove empty link targets by assigning the anchor id to the following sibling or to one of its descendents.
 */
(function () {
    document.querySelectorAll('.pp-link-target').forEach((anchor) => {
        let id = anchor.getAttribute('id');
        let sibling = anchor.nextElementSibling;
        if (sibling.matches('a') && sibling.textContent === "") {
            // The sibling was a link target too (the one for `_LOCALIZED_UID`)
            sibling = sibling.nextElementSibling;
        }
        if (!sibling.getAttribute('id')) {
            sibling.setAttribute('id', id);
            anchor.remove();
            return;
        }
        // The sibling had an id assigned to. Let's try to assign the id to a descendent.
        let children = sibling.querySelectorAll(':not([id]), [id=""]');
        if (children.length) {
            children[0].setAttribute('id', id);
            anchor.remove();
        }
    });
})();
