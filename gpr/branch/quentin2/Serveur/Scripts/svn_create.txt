#!/bin/sh
DOMAIN=$1
IDWEB=$2
echo "Domaine : ";
echo $DOMAIN;
DOMAIN_path="/var/www/"
SVN_BASE=${DOMAIN_path}${DOMAIN}"/web/svn"
REPO_NAME="Projet"
ADMIN_USER="admin"
ADMIN_PASS="admin"
ANON="y"

touch /var/log/projet.log

mkdir -p ${SVN_BASE}
svnadmin create ${SVN_BASE}/${REPO_NAME}
mkdir /tmp/${REPO_NAME}
mkdir /tmp/${REPO_NAME}/branches
mkdir /tmp/${REPO_NAME}/tags
mkdir /tmp/${REPO_NAME}/trunk

cd /tmp/${REPO_NAME}
svn import -m "Initial import" file:///${SVN_BASE}/${REPO_NAME}  2>/var/log/projet.log

rm -rf /tmp/${REPO_NAME}  2>/var/log/projet.log

touch ${SVN_BASE}/${REPO_NAME}/conf/passwd  2>/var/log/projet.log
htpasswd -cbm ${SVN_BASE}/${REPO_NAME}/conf/passwd $ADMIN_USER $ADMIN_PASS  2>/var/log/projet.log
#c pour créer le fichier; b : pour forcer le mode batch; m pour forcer md5

cat >> ${SVN_BASE}/${REPO_NAME}/conf/authz << END_TEXT
[${REPO_NAME}:/]
$ADMIN_USER = rw
END_TEXT

if [ $ANON = "y" ] ; then
echo "* = r" >> ${SVN_BASE}/${REPO_NAME}/conf/authz
fi

chown -R web${IDWEB}:client0 /${SVN_BASE}
chmod -R 770 /${SVN_BASE}

/etc/init.d/apache2 graceful  2>/var/log/projet.log