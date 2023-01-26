/**
 * @see https://yashmahalwal.medium.com/scrollspy-using-intersection-observer-36acb7520e46
 */
pp.domReady(function () {
    const data = JSON.parse(document.getElementById('pp-scrollnav-data').dataset.settings);
    const menu = document.getElementById(data.menuId);
    const menuItems = menu.querySelectorAll('.nav-item');
    const links = [...menu.getElementsByTagName('a')];

    document.body.setAttribute('tabindex', '0');
    document.body.classList.add('pp-has-scrollnav');

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    // Activate the link associated with the section
                    menuItems.forEach((menuItem) => {
                        menuItem.classList.remove('active');
                    });
                    const id = entry.target.getAttribute('id');
                    const link = document.querySelector('[href="#' + id + '"]');
                    const menuItem = link.closest('.nav-item') ?? link.parentElement;
                    menuItem.classList.add('active');
                }
            }
        },
        {
            rootMargin: data.rootMargin,
        }
    );

    links.forEach((link) => {
        // Add section to observer
        observer.observe(document.getElementById(link.href.split('#').pop()));
        // Remove focus from clicked link
        link.addEventListener('click', (e) => {
            e.target.blur();
        })
    });
});
