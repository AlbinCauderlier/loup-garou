@!/bin/bash

case $1 in
  start )
    echo starting loups_garous...
    scl enable rh-python36 ./start.sh
    ;;
  daemon )
    echo starting loups_garous...
    scl enable rh-python36 ./daemon.sh
    ;;
esac
