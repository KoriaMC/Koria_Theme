#!/bin/bash
# Installe le plugin Azuriom "Koria Forum" sur le site KoriaMC.
dmesg -n 1 2>/dev/null
cd /root || exit 1

# Nettoyage d'une ancienne installation au mauvais endroit (panel Pterodactyl)
if [ -d /var/www/pterodactyl/plugins/koriaforum ]; then
  rm -rf /var/www/pterodactyl/plugins/koriaforum
  rmdir /var/www/pterodactyl/plugins 2>/dev/null
  echo "Nettoyage Pterodactyl : OK"
fi

curl -sLo kf.zip https://github.com/KoriaMC/Koria_Theme/archive/koriaforum-plugin.zip || { echo "ERREUR : telechargement"; exit 1; }
rm -rf kf && unzip -q kf.zip -d kf || { echo "ERREUR : unzip"; exit 1; }

# Le site Azuriom = celui qui contient le theme koria
F=$(find /var/www -name theme.json -path "*/resources/themes/koria/*" 2>/dev/null | head -1)
[ -n "$F" ] || { echo "ERREUR : site Azuriom introuvable"; exit 1; }
S=$(cd "$(dirname "$F")/../../.." && pwd)
[ -f "$S/artisan" ] && [ -d "$S/plugins" ] || { echo "ERREUR : $S ne ressemble pas a Azuriom"; exit 1; }
P="$S/plugins/koriaforum"; echo "Site : $S"

rm -rf "$P" && mkdir -p "$P"
cp -a kf/*/. "$P"/ && rm -f "$P/installer.sh"
chown -R --reference="$S/plugins" "$P"
command -v restorecon >/dev/null && restorecon -R "$P"
rm -rf /root/kf /root/kf.zip
cd "$S" && php artisan optimize:clear >/dev/null && echo "PLUGIN FORUM INSTALLE"
