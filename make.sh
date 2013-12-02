#!/bin/sh

echo "Installing clean project"

composer install        && \
npm install             && \
bower install

echo "project successfully installed"

git status