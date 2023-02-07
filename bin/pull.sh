#!/bin/bash

# OSX Utility script - Pulls Uploads and Database out of Kubernetes and into a locally running Docker installation
# Usage: ./bin/pull.sh <kube-namespace> -d <local-url> -u <local-uploads-path>
# Example: ./bin/pull.sh staging -d http://idc.docker.localhost -u ./code
# Example: ./bin/pull.sh production -d http://idc.docker.localhost -u ./code

pullDatabase() {
  DB_HOST=$(kubectl get svc idc-website-mariadb -n $1 --no-headers -o custom-columns=":spec.externalName" --context $3 | sed 1q)
  DB_NAME=$(kubectl get cm idc-website-bedrock-project-wp-config -n $1 --no-headers -o custom-columns=":data.DB_NAME" --context $3 | sed 1q)
  DB_USER=$(kubectl get cm idc-website-bedrock-project-wp-config -n $1 --no-headers -o custom-columns=":data.DB_USER" --context $3 | sed 1q)
  DB_PASS=$(kubectl get secret idc-website-mariadb-secrets -n $1 --template='{{index .data "mariadb-password"}}' --context $3 | base64 -d)

  URL_SEARCH=$(kubectl get cm idc-website-bedrock-project-wp-config -n $1 --no-headers -o custom-columns=":data.WP_HOME" --context $3 | sed 1q)
  URL_REPLACE=$2

  CURRENT_DATE=$(date +'%m-%d-%Y')
  RAND_HASH=$(cat /dev/urandom | env LC_ALL=C tr -dc 'a-zA-Z0-9' | fold -w 8 | head -n 1)

  echo "Creating backups of local and $1 databases..."
  docker compose -f cli.yml run --rm \
    wp db export /backup/$CURRENT_DATE-$RAND_HASH-local.sql
  docker compose -f cli.yml run --rm \
    -e DB_NAME=$DB_NAME \
    -e DB_HOST=$DB_HOST \
    -e DB_USER=$DB_USER \
    -e DB_PASSWORD=$DB_PASS \
    wp db export /backup/$CURRENT_DATE-$RAND_HASH-$1.sql
  echo "Pulling database from ${1}/${DB_NAME} into local DB"
  read -r -p "Proceed? [y/N] " response
  case "$response" in [yY][eE][sS] | [yY])
    docker compose -f cli.yml run --rm wp db import /backup/$CURRENT_DATE-$RAND_HASH-$1.sql
    docker compose -f cli.yml run --rm wp search-replace $URL_SEARCH $URL_REPLACE --all-tables
    ;;
  *)
    echo "Cancelling database pull" 1>&2
    exit 1
    ;;
  esac
}

pullUploads() {
  TARGET_POD=$(kubectl get pods -l app.kubernetes.io/deployment=idc-website-bedrock-project-deploy -n $1 --no-headers -o custom-columns=":metadata.name" --context $3 | sed 1q)
  echo "Pulling uploads from ${1}/${TARGET_POD} into ${2}/uploads"
  read -r -p "Proceed? [y/N] " response
  case "$response" in [yY][eE][sS] | [yY])
    kubectl cp $TARGET_POD:/var/www/html/uploads $2/uploads -n $1 --context $3
    ;;
  *)
    echo "Cancelling upload pull" 1>&2
    exit 1
    ;;
  esac
}

KUBE_NS=$1
shift

while getopts ":d:u:" opt; do
  case ${opt} in
  d)
    pullDatabase "$KUBE_NS" "$OPTARG" "GHDefaultCluster"
    ;;
  u)
    pullUploads "$KUBE_NS" "$OPTARG" "GHDefaultCluster"
    ;;
  \?)
    echo "Invalid option: $OPTARG" 1>&2
    ;;
  :)
    echo "Invalid option: $OPTARG requires an argument" 1>&2
    ;;
  esac
done
shift $((OPTIND - 1))
