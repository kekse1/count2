#!/usr/bin/env bash

#
# Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
# https://kekse.biz/ https://github.com/kekse1/scripts/
# v0.0.1

_regex='$*'

if [[ -z "$_regex" ]]; then
	echo " >> Please argue with \`sed\` compatible regular expression!" >&2
	exit 1
fi

traverse()
{
	cd "$*"

	for i in *; do
		p="$@/$i"
		
		if [[ -L "$p" ]]; then
			continue
		elif [[ -d "$p" ]]; then
			traverse "$p"
		elif [[ -f "$i" ]]; then
			echo "'$p'"
		fi
	done
}

traverse "`pwd`"

