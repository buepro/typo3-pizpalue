;
/**
 * Size the element height to the embedding row height (e.g. a content element in a column will be as high as
 * the row it belongs to). the class pp-row-height is used for that purpose.
 */
pp.domReady(function () {
    function RowElementSizer(row) {
        this.row = row;
        this.elements = [];
    }
    RowElementSizer.prototype.observeImages = function () {
        this.row.querySelectorAll('img').forEach((img) => {
            img.addEventListener('load', () => {
                this.setHeight(this.getMaxHeight());
            })
        })
    }
    RowElementSizer.prototype.getMaxHeight = function () {
        this.elements.forEach((el) => el.style.height = 'auto');
        let maxHeight = 0;
        this.elements.forEach((el) => {
            const height = parseFloat(getComputedStyle(el)['height']);
            if (height > maxHeight) {
                maxHeight = height;
            }
        })
        return maxHeight;
    }
    RowElementSizer.prototype.setHeight = function (height) {
        this.elements.forEach((el) => el.style.height = height + 'px');
    }

    const elements = document.querySelectorAll('.pp-row-height');
    const rows = [];

    if (elements.length) {
        initializeRows();
        window.addEventListener('load', updateAllRows);
        window.addEventListener('resize', updateAllRows);
        rows.forEach((row) => row.ppRowElementSizer.observeImages());
        updateAllRows();
    }

    function initializeRows () {
        elements.forEach((el) => {
            const row = el.closest('.row');
            if (row === null) {
                return;
            }
            if (row.ppRowElementSizer === undefined) {
                row.ppRowElementSizer = new RowElementSizer(row);
                rows.push(row);
            }
            row.ppRowElementSizer.elements.push(el);
        });
    }

    function updateAllRows() {
        rows.forEach((row) => updateRow(row));
    }

    function updateRow(row) {
        row.ppRowElementSizer.setHeight(row.ppRowElementSizer.getMaxHeight());
    }

});

/**
 * Dataprotection label
 */
pp.domReady(() => {
    let textElement = document.querySelector('.pp-label-dataprotection p');
    if (textElement === null) {
        return;
    }
    let label;
    // Backward compatibility
    label = document.querySelector('#idGeneralContactForm-idDataprotectionMultiCheckbox > div label');
    if (label === null) {
        // In case it is a checkbox-multiple
        label = document.querySelector('.pp-dataprotection > div label');
    }
    if (label === null) {
        // In case it is a checkbox (single)
        label = document.querySelector('.pp-dataprotection > label');
    }
    if (label !== null) {
        label.querySelectorAll(`:scope ${'> span'}`)[0].innerHTML = textElement.innerHTML;
    }
});

/**
 * Add main menu state to header
 */
pp.domReady(function () {
    const nav = document.getElementById('mainnavigation');
    if (nav === null) {
        return;
    }
    nav.addEventListener('show.bs.collapse', () => {
        document.getElementById('page-header').classList.add('pp-dropdown-active');
    });
    nav.addEventListener('hidden.bs.collapse', () => {
        document.getElementById('page-header').classList.remove('pp-dropdown-active');
    });
});

/**
 * Link parent with class `frame-container` to url defined with anchor using the class `pp-extend-link`.
 * An alternative link target can be defined adding the class `ppc-el-[targetClass]` to the anchor.
 */
pp.domReady(function () {
    document.querySelectorAll('.pp-extend-link').forEach((el) => {
        if (el.getAttribute('href')) {
            const classAttr = el.getAttribute('class');
            let targetClass = 'frame-container';
            const match = classAttr.match(/ppc-el-([\w|-]+)/);
            if (match && match[1]) {
                targetClass = match[1];
            }
            const target = el.closest('.' + targetClass);
            if (target === null) {
                return;
            }
            target.style.cursor = 'pointer';
            target.addEventListener('click', () => window.location.href = el.getAttribute('href'));
        }
    });
});

/**
 * Bootstrap popovers
 * ------------------
 * Ensures just one popover is open at a time and closes all popovers on clicking outside a popover.
 * Works with popovers bound to elements having the class pp-popover. Example:
 *
 * <a class="pp-popover" data-toggle="popover" data-trigger="click" title="Title" data-content="Content">Text</a>
 */
pp.domReady(function () {
    const popovers = document.querySelectorAll('.pp-popover');
    function hidePopovers () {
        document.querySelectorAll('.pp-popover.ppc-active').forEach((el) => {
            bootstrap.Popover.getOrCreateInstance(el).hide();
            el.classList.remove('ppc-active');
        })
    }
    if (popovers.length) {
        // Just allow one popover to be active at a time
        popovers.forEach((popover) => {
            popover.addEventListener('click', function () {
                if (!this.classList.contains('ppc-active')) {
                    hidePopovers();
                }
                this.classList.toggle('ppc-active');
            })
        });
        // Close popover on clicking outside
        document.addEventListener('click', function (event) {
            const target = event.target;
            let isOutsideClick = pp.parents(target,'.popover').length === 0;
            isOutsideClick = isOutsideClick && !target.classList.contains('pp-popover');
            if (isOutsideClick) {
                hidePopovers();
            }
        })
    }
});
