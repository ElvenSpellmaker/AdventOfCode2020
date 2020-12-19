#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

SCRIPT="$1"

if [ -z "${SCRIPT-}" ]; then
	echo "No test files detected, nothing to test!"
	exit 0
fi

exitCode=0

DAY_NAME="$(basename "$SCRIPT")"
EXPECTED_FILE="s-${DAY_NAME%.php}.txt"

ACTUAL="$(php s-$SCRIPT)"
EXPECTED="$(<"$DIR/$EXPECTED_FILE")"

echo -n "$DAY_NAME: "
if [ "$EXPECTED" == "$ACTUAL" ]; then
	echo -e "\e[0;32mPASS\e[0m"
else
	echo -e "\e[0;31mFAIL\e[0m"
	exitCode=1
fi

exit $exitCode
