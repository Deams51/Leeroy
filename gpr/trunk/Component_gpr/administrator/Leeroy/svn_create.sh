#!/bin/sh
# Usage: svn_create svn1.stic-test.tk 22 rondoudou RondoudouRoxx! 1
# Create a svn repository in web folder of domain given.
PROGNAME=$(basename $0)
DOMAIN=$1
QUOTA_USER=$2
DOMAIN_path="/var/www/"
REPO=$DOMAIN_path""$DOMAIN"/web/svn"
echo 'domaine : '$DOMAIN'
domaine path : '$DOMAIN_path'
repo : '$REPO'
'


function usage {

	# Display usage message on standard error
	echo "Usage: $PROGNAME file" 1>&2 >> /var/log/projet.log
}

function clean_up {

	# Perform program exit housekeeping
	# Optionally accepts an exit status
	rm -rf /tmp/projet
	exit -1;
}

function error_exit {

	# Display error message and exit
	echo "${PROGNAME}: ${1:-"Unknown Error"}" 1>&2  >> /var/log/projet.log
	clean_up 1
}

trap clean_up SIGHUP SIGINT SIGTERM

#Test des arguments en entr�e
if [ $# != "2" ]; then
	usage
	error_exit "Invalid number of arguments"
fi

# if [ -z "$1" ]
  # then
    # echo "No argument supplied"
# fi

test -e /var/log/projet.log || touch /var/log/projet.log

mkdir -p ${REPO} || error_exit "Cannot create dir: "${REPO}
svnadmin create ${REPO}/projet || error_exit "Cannot create svn dir: "${REPO}/projet
mkdir /tmp/projet || error_exit "Cannot create dir: /tmp/projet"
mkdir /tmp/projet/branches || error_exit "Cannot create dir: /tmp/projet/branches"
mkdir /tmp/projet/tags || error_exit "Cannot create dir: /tmp/projet/tags"
mkdir /tmp/projet/trunk || error_exit "Cannot create dir: /tmp/projet/trunk"
cd /tmp/projet || error_exit "Cannot access dir: /tmp/projet"
svn import -m "Initial import" file:///${REPO}/projet || error_exit "Cannot import svn: "file:///${REPO}/projet

# On supprime les dossiers temporaires
rm -rf /tmp/projet || error_exit "Cannot delete: /tmp/projet"
rm ${REPO}/projet/conf/passwd 
rm ${REPO}/projet/conf/authz

ARG1='$''1'
ARG2='$''2'
SIZE='$''SIZE'
SIZE_DEF='`du -sm $REPO | sed -r "s/^([0-9\.]+).+/\1/"`'
QUOTA='$''{''QUOTA''}'
# On ajoute le script de pre-commit 
cat >> ${REPO}/projet/hooks/pre-commit << END_TEXT
#!/bin/sh

REPO="$ARG1"
TXN="$ARG2"

# Set the maximum allowed size for this repository
QUOTA="$QUOTA_USER"
# Measure the current size of this repository to compare against our quota
SIZE=$SIZE_DEF

# If the current size is greater than the quota set then prohibit the transaction
if [ "$SIZE" -gt "$QUOTA" ]; then
  # Send error message to STDERR
  echo "Your usage quota of ${QUOTA}MB has been exceeded for this repository. Ask an admin for more space." 1>&2
  exit 1
fi

# Exit on all errors.
set -e

# All checks passed, so allow the commit.
exit 0
END_TEXT

