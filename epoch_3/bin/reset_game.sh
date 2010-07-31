#!/bin/bash
#


BASE=/home/ww3game/
CACHE=$BASE/php/WW2/cache/

CURRENT_AGE=`cat $CACHE/age.txt`
NEXT_AGE=$((CURRENT_AGE+1))

#echo "<? \$_AGE = 18; ?>" > $CACHE/age.php
#echo "18" > $CACHE/age.txt

echo "Current Age = $CURRENT_AGE"
echo "Next    Age = $NEXT_AGE"

#Update

php -f ~/bin/find-next-reset.php "$CACHE" "$CURRENT_AGE"



cd $BASE/public_html
echo "Doing reset script:"
php -d include_path=".:/home/ww3game/php/WW2/" -f ../php/WW2/scripts/resetscript.php
echo "Moving areas:"
php -d include_path=".:/home/ww3game/php/WW2/" -f ../php/WW2/scripts/move-areas.php

# update the age file. XXX DONT DO THIS HERE
#echo "<? \$_AGE = $NEXT_AGE; ?>" > $CACHE/age.php
#echo $NEXT_AGE > $CACHE/age.txt
