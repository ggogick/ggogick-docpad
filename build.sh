#!/bin/bash
# Simple build script.  Pulls latest from git repository, generates static,
# and handles my php-based contact form.  Potential todo: Handle an argument
# to blow away out/ entirely before generation.

# git pull
git pull
if [ "$?" -ne "0" ]; then
	echo "CRITICAL: git pull has failed"
	exit 1
fi

# generate static
docpad -e static generate
if [ "$?" -ne "0" ]; then
	echo "CRITICAL: docpad generation has failed"
	exit 2
fi

# Because I'm lazy, I'm just shoving this here instead of fiddling with
# docpad events.  
if [[ -e "out/contact/index.html" && -f "out/contact/index.html" ]]; then
	mv -f out/contact/index.html out/contact/index.php
fi
if [ "$?" -ne "0" ]; then
	echo "CRITICAL: copying of contact form has failed"
	exit 3
fi

exit 0
