#!/bin/bash

COMPOSE_FILES="$2"
NAME_PREFIX="$1"

docker-compose -p $NAME_PREFIX $COMPOSE_FILES stop
docker-compose -p $NAME_PREFIX $COMPOSE_FILES rm -f
