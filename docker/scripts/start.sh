#!/bin/bash

WINPTY=''
if [[ -n ${WINDIR} ]]
then
   echo "WINDIR is defined"
   WINPTY='winpty '
fi

COMPOSE_FILES="$2"
NAME_PREFIX="$1"

docker-compose -p $NAME_PREFIX $COMPOSE_FILES stop
docker-compose -p $NAME_PREFIX $COMPOSE_FILES rm -f

docker-compose -p $NAME_PREFIX $COMPOSE_FILES build --pull
docker-compose -p $NAME_PREFIX $COMPOSE_FILES up -d --force-recreate

$WINPTY docker-compose -p $NAME_PREFIX $COMPOSE_FILES exec php-cli sh -c "cd /var/www/service; composer service:deploy-local"