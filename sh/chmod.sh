#!/usr/bin/env bash

_file=666
_dir=777
_exe=700

_files=0
_dirs=0
_exec=0

traverse()
{
	cd "$1"

	if [[ $? -ne 0 ]]; then
		return;
	else
		echo "($_dir) $1"
		chmod $_dir "$1"
		[[ $? -eq 0 ]] && let _dirs=$_dirs+1
	fi

	for i in *; do
		p="$1/$i"

		if [[ -L "$p" ]]; then
			continue;
		elif [[ -d "$p" ]]; then
			traverse "$p"
		elif [[ -f "$p" ]]; then
			if [[ "${p: -3}" == ".sh" ]]; then
				echo "($_exe) $p"
				chmod $_exe "$p"
				[[ $? -eq 0 ]] && let _exec=$_exec+1
			else
				echo "($_file) $p"
				chmod $_file "$p"
				[[ $? -eq 0 ]] && let _files=$_files+1
			fi
		fi
	done
}

traverse "`pwd`"
echo
echo " >> Changed $_dirs directories to ($_dir)"
echo " >> Changed $_files files to ($_file)"
echo " >> Changed $_exec \`.sh\` files to ($_exe)"

