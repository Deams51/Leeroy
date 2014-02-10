#!/bin/sh
DOMAIN=$1
IDWEB=$2
USER=$3
pASS=$4
DOMAIN_path="/var/www/"
SVN_BASE=${DOMAIN_path}${DOMAIN}"/web/svn"

touch /var/log/projet.log

htpasswd -bm ${SVN_BASE}/projet/conf/passwd $USER $pASS  2>/var/log/projet.log
#b : pour forcer le mode batch; m pour forcer md5
echo ${USER}" = rw" >> ${SVN_BASE}/projet/conf/authz
