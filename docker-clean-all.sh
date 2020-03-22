#!/bin/bash

#docker container ls -a
#docker container rm ... ...

#docker container ls -a --filter status=exited --filter status=created
#docker container prune
#docker container prune --filter "until=12h"

docker container stop $(docker container ls -aq)
docker container rm $(docker container ls -aq)

#docker image ls
#docker image prune
docker image prune -a
#docker image prune -a --filter "until=12h"

#docker volume ls
docker volume prune

#docker network ls
docker network prune
#docker network prune -a --filter "until=12h"

#docker system prune
docker system prune --volumes
