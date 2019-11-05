#!/bin/bash
git add .
git commit -am "Flash"
git push origin master
ssh root@laravelnepal.com
cd /var/www/html/nepse-analysis
git pull origin master
yarn build