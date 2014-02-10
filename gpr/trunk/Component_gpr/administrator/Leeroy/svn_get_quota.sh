#!/bin/sh
DOMAIN=$1
DOMAIN_path="/var/www/"
REPO=$DOMAIN_path""$DOMAIN"/web/svn/projet"

echo 'get_quota.sh'
echo 'domaine :'$DOMAIN
echo 'repo :'$REPO

exit `du -sm $REPO | sed -r "s/^([0-9\.]+).+/\1/"`