#!/usr/bin/env bash

# Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
# https://kekse.biz/ https://github.com/kekse1/count2/
# v2.0.0
#
# Important: this file *needs* to reside directly in the
# 'php/' directory!

#
php="`which php 2>/dev/null`"

if [[ -z "$php" ]]; then
	echo " >> No PHP interpreter \`php\` found." >&2
	exit 1
fi

target="$(basename "$0" .sh)"

if [[ ! -L "$0" || "$target" == "main" ]]; then
	echo " >> Please don't call this script directly.. use the symlinks." >&2
	exit 2
fi

real="$(realpath "$0")"
dir="$(dirname "$real")"
path="${dir}/${target}"

if [[ ! -d "$path" ]]; then
	echo " >> The target '$target' doesn't exist." >&2
	exit 3
fi

cmd="${path}/main.php"

if [[ ! -r "$cmd" ]]; then
	echo " >> Can't access the main PHP script of the target '$target'." >&2
	exit 4
fi

cmd="'$php' $cmd"

for i in "$@"; do
	cmd="${cmd} '$i'"
done

eval "$cmd"

