(function () {
    'use strict';

    // Petite notification en bas de l'écran
    var toastTimer = null;
    function toast(message) {
        var el = document.getElementById('koriaToast');
        if (!el) return;
        el.textContent = message;
        el.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { el.classList.remove('is-visible'); }, 2200);
    }

    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise(function (resolve, reject) {
            var area = document.createElement('textarea');
            area.value = text;
            area.setAttribute('readonly', '');
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            try { document.execCommand('copy') ? resolve() : reject(); } catch (e) { reject(e); }
            document.body.removeChild(area);
        });
    }

    // Boutons « Copier l'IP »
    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-copy-ip]');
        if (!button) return;
        var ip = button.getAttribute('data-copy-ip');
        copyText(ip).then(function () {
            toast('IP copiée : ' + ip);
        }, function () {
            toast('IP du serveur : ' + ip);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Barre de navigation opaque après défilement
        var nav = document.getElementById('koriaNav');
        if (nav) {
            var onScroll = function () { nav.classList.toggle('is-scrolled', window.scrollY > 40); };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        }

        // Apparition des sections au défilement
        var items = document.querySelectorAll('.k-reveal');
        if (!('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        items.forEach(function (el) { observer.observe(el); });
    });
})();
