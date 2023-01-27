(function () {
    function getTitleElement(collapsible, selector) {
        return collapsible.closest('.pp-page-ce').querySelector('.' + selector);
    }
    const pageCeCollapsibles = document.querySelectorAll('.pp-page-ce .ppc-collapse');
    pageCeCollapsibles.forEach((el) => {
        el.addEventListener('show.bs.collapse', (e) => {
            getTitleElement(e.target, 'ppc-expanded').classList.remove('d-none');
            getTitleElement(e.target, 'ppc-collapsed').classList.add('d-none');
        });
        el.addEventListener('hide.bs.collapse', (e) => {
            getTitleElement(e.target, 'ppc-expanded').classList.add('d-none');
            getTitleElement(e.target, 'ppc-collapsed').classList.remove('d-none');
        });
    })
})();
