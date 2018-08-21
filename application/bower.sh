#!/usr/bin/env bash
ARGS="$@"

if [ $# -eq 0 ]; then
	COMMAND="install"
	else
	COMMAND=$ARGS
	fi

docker run --rm --interactive --tty \
        --volume $PWD/data:/data \
        -w=/data/assets \
        --user=$(id -u):$(id -g) \
        --entrypoint "node_modules/bower/bin/bower" \
        node $COMMAND  --allow-root
