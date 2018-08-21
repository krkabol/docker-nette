#!/usr/bin/env bash
ARGS="$@"

if [ $# -eq 0 ]; then
	COMMAND="install"
	else
	COMMAND=$ARGS
	fi

docker run --rm --interactive --tty \
        --volume $PWD/data/assets:/data \
        -w=/data \
        --user=$(id -u):$(id -g) \
        --entrypoint "npm" \
        node $COMMAND  --no-bin-link
