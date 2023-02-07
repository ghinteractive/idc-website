#!/bin/bash

# OSX Utility script - Manually pushes to ECR
# Usage: ./bin/build.sh <tag> --build-arg KEY=VALUE
# Example: ./bin/build.sh manual-release --build-ARG ACF_PRO_KEY=<key> --build-arg FONTAWESOME_NPM_AUTH_TOKEN=<key>

ECR_URI=557798128580.dkr.ecr.us-east-1.amazonaws.com
ECR_IMAGE=idc-website

ECR_TAG="${ECR_IMAGE}:${1:-latest}"
ECR_FULL_IMAGE="${ECR_URI}/${ECR_TAG}"

aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin $ECR_URI
docker build -t $ECR_TAG "${@:2}" ./code
docker tag $ECR_TAG $ECR_FULL_IMAGE
docker push $ECR_FULL_IMAGE