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
  ln -s package-vue.json package.json
  ln -s vite-vue.config.ts vite.config.ts
  ln -s tsconfig-vue.json tsconfig.json
  ln -s eslint-vue.config.js eslint.config.js
  ln -s js-vue resources/js
  echo "✅  Now using Vue"
}

function phpunit() {
  cleanup_tests
  ln -s tests-phpunit tests
  echo "✅  Now using PHPUnit"
}

function pest() {
  cleanup_tests
  ln -s tests-pest tests
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
