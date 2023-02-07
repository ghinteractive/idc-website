#!/bin/bash

# OSX Utility script - Runs a Docker CLI service
# Usage: ./bin/cli.sh <service> ...
# Example: ./bin/cli.sh node npm run build

docker compose -f cli.yml run --rm $@