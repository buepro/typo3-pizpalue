(function () {
    document.querySelectorAll('.pp-modal-dialog.ppc-iframe').forEach(
        iFrame => iFrame.addEventListener('show.bs.modal', e => {
            const iFrame = e.target.querySelector('iframe');
            if (iFrame.src === '') {
                iFrame.src = iFrame.dataset.ppSrc;
            }
        }))
})();
