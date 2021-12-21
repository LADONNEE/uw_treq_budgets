#!/bin/bash

# Copy directories no in source control into place
CopiedFromLive vendor

# Confirm links to /www/local
FileDeleted storage
LinkExists storage /www/local/budgets/storage
LinkExists .env /www/local/budgets/.env

# bootstrap cache directory writable by web server
DirectoryExists bootstrap/cache
WebWritable bootstrap/cache

# Clean up logs
LogFilesPruned 30 '/www/local/budgets/storage/logs/*'
LogFileRotated /www/local/budgets/storage/logs/laravel.log

# Get rid of compiled views
FileDeleted /www/local/budgets/storage/framework/views/*

# chown entire build directory to weblee
OwnedByWeblee .

ResourceCacheIncremented /www/local/budgets/.env

MakeBuildLive

CdAndConfirm ${DIR_LIVE}

#php artisan config:cache
#php artisan route:cache

sudo systemctl restart supervisord
LastCommandSuccess $? "systemctl restart supervisord"
echo ". supervisord restarted"
