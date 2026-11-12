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

    // Remplace le nom de la monnaie (ex. « points boutique ») par l'icône de pièce
    var SKIP_TAGS = { SCRIPT: 1, STYLE: 1, TEXTAREA: 1, OPTION: 1, TITLE: 1, NOSCRIPT: 1 };

    function replaceMoney(root, name, icon) {
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
            acceptNode: function (node) {
                var parent = node.parentNode;
                if (!parent || SKIP_TAGS[parent.nodeName] || parent.isContentEditable) return NodeFilter.FILTER_REJECT;
                if (parent.closest && parent.closest('.k-money')) return NodeFilter.FILTER_REJECT;
                return node.nodeValue.indexOf(name) !== -1 ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
            }
        });
        var nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);

        nodes.forEach(function (node) {
            var parts = node.nodeValue.split(name);
            var fragment = document.createDocumentFragment();
            parts.forEach(function (part, i) {
                if (part) fragment.appendChild(document.createTextNode(part));
                if (i < parts.length - 1) {
                    var img = document.createElement('img');
                    img.src = icon;
                    img.alt = name;
                    img.title = name;
                    img.className = 'k-money';
                    fragment.appendChild(img);
                }
            });
            node.parentNode.replaceChild(fragment, node);
        });
    }

    function initMoneyIcon() {
        var body = document.body;
        var name = body.getAttribute('data-money-name');
        var icon = body.getAttribute('data-money-icon');
        if (!name || !icon || !name.trim()) return;

        replaceMoney(body, name, icon);

        // Contenu chargé après coup (panier, modales…)
        if ('MutationObserver' in window) {
            new MutationObserver(function (mutations) {
                mutations.forEach(function (m) {
                    m.addedNodes.forEach(function (added) {
                        if (added.nodeType === 1) replaceMoney(added, name, icon);
                        else if (added.nodeType === 3 && added.parentNode) replaceMoney(added.parentNode, name, icon);
                    });
                });
            }).observe(body, { childList: true, subtree: true });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMoneyIcon();

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
