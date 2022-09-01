(function () {
    function getTitleElement(collapsible) {
        return collapsible.closest('.pp-page-ce').querySelector('.ppc-title');
    }
    const pageCeCollapsibles = document.querySelectorAll('.pp-page-ce .ppc-collapse');
    pageCeCollapsibles.forEach((el) => {
        el.addEventListener('show.bs.collapse', (e) => getTitleElement(e.target).classList.add('d-none'));
        el.addEventListener('hide.bs.collapse', (e) => getTitleElement(e.target).classList.remove('d-none'));
    })
})();
