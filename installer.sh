#!/bin/bash
# Installe le thème KoriaMC (branche wiki-plus-beau) sur le site Azuriom.
dmesg -n 1 2>/dev/null
cd /root || exit 1
curl -sLo k.zip https://github.com/KoriaMC/Koria_Theme/archive/wiki-plus-beau.zip || { echo "ERREUR : telechargement"; exit 1; }
rm -rf kt && unzip -q k.zip -d kt || { echo "ERREUR : unzip"; exit 1; }
F=$(find / -name theme.json -path "*themes/koria/*" -not -path "/proc/*" -not -path "/root/*" 2>/dev/null | head -1)
[ -n "$F" ] || { echo "ERREUR : theme introuvable"; exit 1; }
T=$(dirname "$F"); echo "Theme : $T"
rm -rf /root/koria-sauvegarde-2 && cp -a "$T" /root/koria-sauvegarde-2
cp -a kt/*/. "$T"/ && rm -f "$T/installer.sh"
chown -R --reference="$T/.." "$T"
cd "$T/../../.." && php artisan view:clear && echo "THEME INSTALLE"
