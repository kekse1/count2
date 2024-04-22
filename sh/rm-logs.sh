#!/usr/bin/env bash

while IFS= read -r -d '' file; do
	rm -v "$file"
done < <(find -name '*.log' -type f -print0)

