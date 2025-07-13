#!/bin/bash

function cleanup() {
  rm -f package.json
  rm -f vite.config.ts
  rm -f tsconfig.json
  rm -f eslint.config.js
  rm -rf resources/js
}

function react() {
  cleanup
  ln -s package-react.json package.json
  ln -s vite-react.config.ts vite.config.ts
  ln -s tsconfig-react.json tsconfig.json
  ln -s eslint-react.config.js eslint.config.js
  ln -s js-react resources/js
  echo "✅  Now using React"
}

function vue() {
  echo "Using Vue"
  # Add your Vue setup commands here
}

if [ "$1" == "react" ]; then
  react
elif [ "$1" == "vue" ]; then
  vue
else
  echo "Usage: $0 {react|vue}"
  exit 1
fi
