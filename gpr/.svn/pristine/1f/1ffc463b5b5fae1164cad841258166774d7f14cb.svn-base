#!/bin/sh
DOMAIN=$1
USER=$2
PASS=$3
DOMAIN_path="/var/www/"
REPO=$DOMAIN_path""$DOMAIN"/web/svn/projet"

# test si fichier existe
test -e /var/log/projet.log || touch /var/log/projet.log

# test si fichier existe
if test -e ${REPO}/conf/passwd
then
	echo 'existe'
	htpasswd -bm ${REPO}/conf/passwd $USER $PASS  2>&1 >> /var/log/projet.log	
	#b : pour forcer le mode batch; m pour forcer md5
else
	echo "n'existe pas"
	htpasswd -cbm ${REPO}/conf/passwd $USER $PASS  2>&1 >> /var/log/projet.log
	#c: pour créer le fichier; b: pour forcer le mode batch; m: pour forcer md5
fi

# test si fichier existe
if test -e ${REPO}/conf/authz
then
	# Il existe on ajoute le membre
	echo ${USER}" = rw" >> ${REPO}/conf/authz
else
	# N'existe pas, on crée le fichier et rajoute le membre
	cat >> ${REPO}/conf/authz << END_TEXT
[/]
$USER = rw
END_TEXT
fi
exit 0
#fini