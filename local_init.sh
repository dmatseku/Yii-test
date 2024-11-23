#!/bin/sh

php yii migrate
php yii migrate --migrationPath=@yii/rbac/migrations
php yii rbac/init