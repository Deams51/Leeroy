#!/bin/sh
# Usage: svn_create svn1.stic-test.tk 22 rondoudou RondoudouRoxx! 1
# Create a svn repository in web folder of domain given.
PROGNAME=$(basename $0)
DOMAIN=$1
IDWEB=$2
DOMAIN_path="/var/www/"
REPO=$DOMAIN_path""$DOMAIN"/web/svn"

echo 'DOMAIN : '$DOMAIN;
echo 'id web : '$IDWEB;

function usage {

	# Display usage message on standard error
	echo "Usage: $PROGNAME file" 1>&2 >> /var/log/projet.log
}

function clean_up {

	# Perform program exit housekeeping
	# Optionally accepts an exit status
	rm -rf /tmp/projet
	exit -1:
}

function error_exit {

	# Display error message and exit
	echo "${PROGNAME}: ${1:-"Unknown Error"}" 1>&2  >> /var/log/projet.log
	clean_up 1
}

trap clean_up SIGHUP SIGINT SIGTERM

#Test des arguments en entrée
if [ $# != "2" ]; then
	usage
	error_exit "Invalid number of arguments"
fi

chown -R web${IDWEB}:client0 /${REPO}  || error_exit "Cannot set owner"
chmod -R 770 /${REPO} || error_exit "Cannot set permissions"