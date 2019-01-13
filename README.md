## Requirements:
* Docker;
* Docker compose.

## How to execute:
* Run ```sudo docker-compose up```

## How to install (only if no installation was already made):
* Enter in DB container ``` docker container exec -it red_php bash ``` and create database 'magento'

## Using Redis
Page & Default caches:
run: ``` bin/magento setup:config:set --page-cache=redis --page-cache-redis-server=red_redis --page-cache-redis-db=1 ```
run: ``` bin/magento setup:config:set --cache-backend=redis --cache-backend-redis-server=red_redis --cache-backend-redis-db=0 ```

Session:
run: ``` bin/magento setup:config:set --session-save=redis --session-save-redis-host=red_redis --session-save-redis-log-level=3 --session-save-redis-db=2 ```

