#!/bin/bash
# Installe le plugin Azuriom "Koria Forum" sur le site KoriaMC.
dmesg -n 1 2>/dev/null
cd /root || exit 1
curl -sLo kf.zip https://github.com/KoriaMC/Koria_Theme/archive/koriaforum-plugin.zip || { echo "ERREUR : telechargement"; exit 1; }
rm -rf kf && unzip -q kf.zip -d kf || { echo "ERREUR : unzip"; exit 1; }
F=$(find / -name artisan -path "*/var/www/*" -not -path "/proc/*" 2>/dev/null | head -1)
[ -n "$F" ] || { echo "ERREUR : site introuvable"; exit 1; }
S=$(dirname "$F"); P="$S/plugins/koriaforum"; echo "Site : $S"
mkdir -p "$S/plugins" && rm -rf "$P" && mkdir -p "$P"
cp -a kf/*/. "$P"/ && rm -f "$P/installer.sh"
chown -R --reference="$S/plugins/.." "$P"
command -v restorecon >/dev/null && restorecon -R "$P"
rm -rf /root/kf /root/kf.zip
cd "$S" && php artisan optimize:clear >/dev/null && echo "PLUGIN FORUM INSTALLE"
