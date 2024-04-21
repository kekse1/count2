#!/usr/bin/env bash

_test=1
_log="rename.log"

[[ $_test -ne 0 ]] && echo -e " >> TEST drive.. no real \`mv\` occure here!\n" >&2

for i in *.inc.php; do
	name="${i%%.inc.php*}"
	cmd="mv -v \"$i\" \"${name}.php\""
	if [[ $_test -eq 0 ]]; then
		eval "$cmd"
	else
		echo "'$cmd'"
	fi
	[[ $? -ne 0 ]] && name="!$name"
	echo $name >>"$_log"
done

