#!/bin/sh
set -e

# Ensure dependencies are installed
composer install --optimize-autoloader

exec "$@"