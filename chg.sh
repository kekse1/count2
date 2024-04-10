#!/usr/bin/env bash

traverse()
{
	cd "$1"

	if [[ $? -ne 0 ]]; then
		return;
	else
		chmod 700 "$1"
	fi

	for i in *; do
		p="$1/$i"

		if [[ -L "$p" ]]; then
			continue;
		elif [[ -d "$p" ]]; then
			traverse "$p"
		elif [[ -f "$p" ]]; then
			chmod 600 "$p"
		fi
	done
}

traverse "`pwd`"

