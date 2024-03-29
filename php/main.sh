#!/usr/bin/env bash

# Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
# https://kekse.biz/ https://github.com/kekse1/count2/
# v2.0.0

#
target=""
script=""
php="`which php 2>/dev/null`"

if [[ -z "$php" ]]; then
	echo " >> No PHP interpreter \`php\` found." >&2
	exit 1
fi

#TODO#!!

if [[ ! -r "$script" ]]; then
	echo " >> The script '$script' can\'t be read." >&2
	exit 2
fi

cmd="${php} '${script}'"

for i in "$@"; do
	cmd="${cmd} '$i'"
done

eval "$cmd"

