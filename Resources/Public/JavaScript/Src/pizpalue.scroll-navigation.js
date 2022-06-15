(function () {
    const data = JSON.parse(document.getElementById('pp-scrollnav-data').dataset.settings);
    const menu = document.getElementById(data.menuId);
    if (!menu) {
        return;
    }
    document.body.setAttribute('tabindex', '0');
    document.body.classList.add('pp-has-scrollnav');
    window.addEventListener('activate.bs.scrollspy', (e) => {
        const a = menu.querySelector('[href="' +  e.relatedTarget + '"]');
        if (!a) {
            return;
        }
        menu.querySelectorAll('.nav-item').forEach((el) => {
            el.classList.remove('active');
            if (!el.querySelector('[href="' +  e.relatedTarget + '"]')) {
                el.getElementsByTagName('a')[0].blur();
            }
        });
        const item = a.closest('.nav-item') ?? a.parentElement;
        if (item) {
            item.classList.add('active');
        }
    });
    new bootstrap.ScrollSpy(document.body, {
        target: '#' + data.menuId,
        offset: parseInt(data.offset)
    });
})();
