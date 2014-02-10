#!/bin/sh
DOMAIN=$1
IDWEB=$2
ADMIN_USER=$3
ADMIN_PASS=$4
DROITS=$5

DOMAIN_path="/var/www/"
SVN_BASE=${DOMAIN_path}${DOMAIN}"/web/svn"

touch /var/log/projet.log

mkdir -p ${SVN_BASE}
svnadmin create ${SVN_BASE}/projet
mkdir /tmp/projet
mkdir /tmp/projet/branches
mkdir /tmp/projet/tags
mkdir /tmp/projet/trunk

cd /tmp/projet
svn import -m "Initial import" file:///${SVN_BASE}/projet  2>/var/log/projet.log

rm -rf /tmp/projet  2>/var/log/projet.log

touch ${SVN_BASE}/projet/conf/passwd  2>/var/log/projet.log
htpasswd -cbm ${SVN_BASE}/projet/conf/passwd $ADMIN_USER $ADMIN_PASS  2>/var/log/projet.log
#c pour créer le fichier; b : pour forcer le mode batch; m pour forcer md5

cat >> ${SVN_BASE}/projet/conf/authz << END_TEXT
[/]
$ADMIN_USER = rw
END_TEXT

if [ $DROITS = "1" ] ; then
echo "* = r" >> ${SVN_BASE}/projet/conf/authz
fi

chown -R web${IDWEB}:client0 /${SVN_BASE}
chmod -R 770 /${SVN_BASE}