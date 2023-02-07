ARG NODE_VERSION=${NODE_VERSION:-10}

FROM node:${NODE_VERSION:-10}-slim AS theme

ARG FONTAWESOME_NPM_AUTH_TOKEN
ENV FONTAWESOME_NPM_AUTH_TOKEN=${FONTAWESOME_NPM_AUTH_TOKEN}

WORKDIR /theme

RUN apt-get update && \
    apt-get install -y git libnotify-bin && \
    npm config set '@fortawesome:registry' 'https://npm.fontawesome.com/' && \
    npm config set '//npm.fontawesome.com/:_authToken' "${FONTAWESOME_NPM_AUTH_TOKEN}" && \
    npm i -g bower gulp@4