#!/bin/sh
set -e

# Ensure dependencies are installed
composer install --no-dev --optimize-autoloader

exec "$@"