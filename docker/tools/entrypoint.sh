#!/bin/bash
set -e

: ${WWW_DATA_UID:=`stat -c %u /www`}
: ${WWW_DATA_GUID:=`stat -c %g /www`}

# Change www-data's uid & guid to be the same as directory in host or the configured one
# Fix cache problems
if [ "`id -u www-data`" != "$WWW_DATA_UID" ]; then
    usermod -u $WWW_DATA_UID www-data || true
fi

if [ "`id -u www-data`" != "$WWW_DATA_GUID" ]; then
    groupmod -g $WWW_DATA_GUID www-data || true
fi

# execute all command with user www-data
if [ "$1" = "apache2-foreground" ]; then
    exec "$@"
else
    su www-data -s /bin/bash -c "$*"
fi
