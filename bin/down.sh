#!/bin/bash

# OSX Utility script - Stops a local installation
# Usage: ./bin/down.sh

docker compose -f traefik.yml down -v