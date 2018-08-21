#!/usr/bin/env bash
ARGS="$@"

if [ $# -eq 0 ]; then
	COMMAND="default"
	else
	COMMAND=$ARGS
	fi

docker run --rm  \
        --volume $PWD/data:/data \
        -w=/data/assets \
        --user=$(id -u):$(id -g) \
        --entrypoint "node_modules/grunt-cli/bin/grunt" \
        node $COMMAND

