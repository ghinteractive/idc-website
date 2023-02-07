#!/bin/bash

# OSX Utility script - Installs all dependencies, starts the project, and pulls down all data from an environment
# Usage: ./bin/setup.sh <environment>
# Example: ./bin/setup.sh production

KUBE_ENVIRONMENT=${1:-production}
SUCCESS='\033[0;32m'
INFO='\033[1;33m'
NC='\033[0m' # No Color

echo -e "Installing Dependencies\n"
./bin/cli.sh composer install
./bin/cli.sh node yarn install
./bin/cli.sh node bower install --allow-root
./bin/cli.sh node yarn run build

echo -e "Spinning up the project over Traefik\n"
./bin/up.sh

echo -e "Warming up.....\n"
sleep 5

echo -e "Pulling from ${INFO}${KUBE_ENVIRONMENT}${NC}\n"
./bin/pull.sh ${KUBE_ENVIRONMENT} -d http://idc.docker.localhost -u ./code
echo -e "${SUCCESS}Success!${NC} You're all set!"