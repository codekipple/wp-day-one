#!/bin/sh

echo "Installing clean project"

composer install        && \
npm install             && \
bower install           && \
grunt build

echo "project successfully installed"

git status