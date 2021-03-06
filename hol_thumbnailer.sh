#!/bin/bash
#
# holarse-faboulous-screenshot-getter Bash Script.
#
# Benötigt imagemagick und pageres-cli (https://github.com/sindresorhus/pageres-cli)

# Pfad zum speicherort der Thumbnails, mit abschließendem /
path="/var/www/html/images/"

# Benötigt damit pageres einen Ort zum cachen hat
export XDG_CONFIG_HOME=/tmp

# Überprüfen ob Daten übergeben wurden.
# $1 = URL, $2 = Viewport, $3 = Thumbnailsize, $4 = Delay
if [ -z "$1" ] && [ -z "$2" ] && [ -z "$3" ]; then
    exit 1
else
    url=$1
    viewport=$2
    thumb=$3
    delay=$4
fi

# Zum Bilderverzichnis wechseln, wo die Thumbnails gespeichert werden
cd "$path"

# MD5 der Url generieren
md5=$(echo -n $url | md5sum | awk '{ print $1}')

# Screenshot der Url erstellen und als $md5.png speichern
pageres --delay=$delay --crop --filename=$md5 $url $viewport

# Falls das erstellen geklappt hat,
# den Screenshot zu einem Thumbnail verkleinern
if [ -e "$path$md5.png" ]; then
    convert $md5.png -resize $thumb $md5.png
fi
