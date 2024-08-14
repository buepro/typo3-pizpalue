(function () {
    const collapsedIds = {
        _ids: [],
        _saveIds: function () {
            document.cookie = 'ppCollapsed=' + JSON.stringify(this._ids);
        },
        init: function () {
            this._ids = JSON.parse(document.cookie.split('; ').find((row) => row.startsWith('ppCollapsed'))?.split('=')[1] ?? '[]');
            return this;
        },
        get: function () {
            return this._ids;
        },
        add: function (id) {
            if (this._ids.indexOf(id) >= 0) {
                return;
            }
            this._ids.push(id);
            this._saveIds();
        },
        remove: function (id) {
            let index = this._ids.indexOf(id);
            if ( index >= 0) {
                this._ids.splice(index, 1);
                this._saveIds();
            }
        }
    }
    document.querySelectorAll('.pp-collapse').forEach((el) => {
        el.addEventListener('show.bs.collapse', (e) => {
            e.stopPropagation();
            const id = e.target.dataset.ppId;
            document.getElementById('pp-header-title-' + id).classList.remove('ppc-collapsed');
            collapsedIds.remove(id);
        });
        el.addEventListener('hide.bs.collapse', (e) => {
            e.stopPropagation();
            const id = e.target.dataset.ppId;
            document.getElementById('pp-header-title-' + id).classList.add('ppc-collapsed');
            collapsedIds.add(id);
        });
    })
    collapsedIds.init().get().forEach(id => {
        const toggler = document.getElementById('pp-toggler-' + id);
        if (!toggler) {
            return;
        }
        toggler.classList.add('collapsed');
        toggler.setAttribute('aria-expanded', 'false');
        document.getElementById('pp-header-title-' + id).classList.add('ppc-collapsed');
        document.getElementById('pp-collapse-' + id).classList.remove('show');
    });
})();
