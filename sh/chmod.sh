#!/usr/bin/env bash

_file=666
_dir=777
_exe=700

_files=0
_dirs=0
_exec=0
_errs=0

traverse()
{
	cd "$1"

	if [[ $? -ne 0 ]]; then
		return;
	else
		echo "($_dir) $1"
		chmod $_dir "$1"

		if [[ $? -eq 0 ]]; then
			let _dirs=$_dirs+1
		else
			let _errs=$_errs+1
		fi
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
				if [[ $? -eq 0 ]]; then
					let _exec=$_exec+1
				else
					let _errs=$_errs+1
				fi
			else
				echo "($_file) $p"
				chmod $_file "$p"
				if [[ $? -eq 0 ]]; then
					let _files=$_files+1
				else
					let _errs=$_errs+1
				fi
			fi
		fi
	done
}

traverse "`pwd`"
echo
echo " >> Changed $_dirs directories to ($_dir)"
echo " >> Changed $_files files to ($_file)"
echo " >> Changed $_exec \`.sh\` files to ($_exe)"
[[ $_errs -ne 0 ]] && echo " >> But $_errs errors happened.." >&2

