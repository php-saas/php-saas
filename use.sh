#!/bin/bash

function cleanup_frontend() {
  rm -f package.json
  rm -f vite.config.ts
  rm -f tsconfig.json
  rm -f eslint.config.js
  rm -rf resources/js
}

function cleanup_tests() {
  rm -f tests
}

function react() {
  cleanup_frontend
  ln -s package-react.json package.json
  ln -s vite-react.config.ts vite.config.ts
  ln -s tsconfig-react.json tsconfig.json
  ln -s eslint-react.config.js eslint.config.js
  ln -s js-react resources/js
  echo "✅  Now using React"
}

function vue() {
  cleanup_frontend
  echo "✅  Now using Vue"
}

function phpunit() {
  echo "✅  Now using PHPUnit"
}

function pest() {
  echo "✅  Now using Pest"
}

if [ "$1" == "react" ]; then
  react
elif [ "$1" == "vue" ]; then
  vue
elif [ "$1" == "phpunit" ]; then
  phpunit
elif [ "$1" == "pest" ]; then
  pest
else
  echo "Usage: $0 {react|vue|phpunit|pest}"
  exit 1
fi
