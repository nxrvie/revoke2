/* CyberShield — interactions légères côté navigateur */
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 900 && sidebar.classList.contains('open') &&
                !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    document.addEventListener('submit', function (e) {
        const btn = e.target.querySelector('[data-confirm]');
        if (btn && !window.confirm(btn.dataset.confirm || 'Confirmer cette action ?')) {
            e.preventDefault();
        }
    });

    document.querySelectorAll('.flash').forEach(function (el) {
        setTimeout(function () { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; }, 6500);
        setTimeout(function () { el.remove(); }, 7000);
    });

    // Sauvegarde de la recherche dans l'URL (déjà gérée par les formulaires GET)
    document.querySelectorAll('[data-autosubmit]').forEach(function (el) {
        el.addEventListener('change', function () { el.form.submit(); });
    });
});
