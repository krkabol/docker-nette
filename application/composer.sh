#!/usr/bin/env bash
ARGS="$@"

if [ $# -eq 0 ]; then
	COMMAND="install"
	else
	COMMAND=$ARGS
	fi

docker exec nette_application bash -c "composer $COMMAND"