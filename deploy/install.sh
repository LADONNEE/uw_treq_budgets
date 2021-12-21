#!/bin/bash

# Make Local Directories
mkdir -p /www/local/budgets
if test -d "/www/local/budgets/storage"
then
    rm -rf storage
else
    mv storage /www/local/budgets
fi
mkdir -p /www/local/budgets/storage/logs
touch /www/local/budgets/storage/logs/laravel.log
mkdir -p /www/local/budgets/storage/framework/views
chown -R apache:apache /www/local/budgets/storage

# Stub .env file
touch /www/local/budgets/storage/.env

# Confirm links to /www/local
LinkExists storage /www/local/budgets/storage
LinkExists .env /www/local/budgets/.env

# bootstrap cache directory writable by web server
DirectoryExists bootstrap/cache
WebWritable bootstrap/cache

# chown entire build directory to weblee
OwnedByWeblee .

# Install Vendor Packages from Composer
sudo -u weblee /home/weblee/bin/composer selfupdate
sudo -u weblee /home/weblee/bin/composer install

ResourceCacheIncremented /www/local/budgets/.env

# Stub live directory for rotation logic
mkdir -p /www/budgets
chown -R weblee:weblee /www/budgets

MakeBuildLive

CdAndConfirm ${DIR_LIVE}

echo ** You must configure your local .env file: /www/local/budgets/storage/.env **
