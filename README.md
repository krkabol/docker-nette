docker-nette 
---

Set of Docker containers demonstrating how to run Nette web application in develop mode by Docker.

## Prerequisites
- installed docker+docker-compose; runable by actual user (see [docker_cheatsheet.md])
- created docker network "dockernet" allowing to access host from containers on 192.168.0.1
```bash
docker network create -d bridge --subnet 192.168.0.0/24 --gateway 192.168.0.1 dockernet
```
- Elasticsearch requires ```sudo sysctl -w vm.max_map_count=262144```
- ```echo 'vm.max_map_count=262144' >> /etc/sysctl.conf``` (to persist reboots)

## Install
- clone this repository 
```bash
git clone https://github.com/krkabol/docker-nette.git 
```
- type ```bash docker-compose up -d``` and wait a while until all dependencies downloaded

#### type in bash:
 ```bash
 cd application/data
 chmod 777 -R htdocs/temp htdocs/log
 ./composer.sh 
 ./npm.sh
./bower.sh
./grunt.sh
 ```
- connect to PHP container and prepare base schema of database:
```bash
    docker exec -i -t nette_application /bin/bash -c "php www/index.php orm:schema-tool:create && exit" 
``` 
- fill database with test data:
    - enjoy PHP Adminer (one of already running container) on this [link](http://localhost:8181/?pgsql=postgres&username=nette&db=nette&ns=main):         
       ```sql   
          INSERT INTO main.users_role (id, name, description, succession, css_class) VALUES
          (1,	'guest','může prohlížet',	1, 'bg-warning'),
          (2,	'user',	'může editovat',	2, 'bg-success'),
          (3,	'manager', 'prohlíží, edituje a něco i spravuje',	3, 'bg-info'),
          (4,	'admin','může vše',	4, 'bg-primary');
          
          INSERT INTO main.users (id, role_id, email, password, deleted, name, surname) VALUES
          (1, 4, 'test@test.cz',	'$2y$10$LY9lNr6lJmBp1tT2vto.r.qULh9hjS52JuGL8VOqNjfu5Yyrt.P72',	FALSE,	'Karel',	'Zkoušečka');          
       ```
   - the password value is a result of  ```Nette\Security\Passwords::hash('test') ``` 

  
## Usage
### Links
```
127.0.0.1:80   app
127.0.0.1:8181   adminer
```

### Logging
Logs of all containers as well as of the sample app are saved by Fluent to the Elasticsearch container. You can access them by Kibana at <localhost:5601>, as prefix use  ```nette-*``` (set in ./fluentd/conf/fluent.conf -> ```logstash_prefix nette```)
- more info at <http://docs.fluentd.org/v0.12/articles/docker-logging-efk-compose>, <http://docs.fluentd.org/v0.12/articles/php>, [linking Nette to Fluent](https://filip-prochazka.com/blog/newrelic-monitoring-aplikace-na-nette-frameworku)


### XDebug
Xdebug is running in the PHP container, to use it by PHPStorm you need:
- use default settings of PHPStorm like port 9000 
- PHPStorm->Run->EditConfigurations->"plus" button->PHP Remote Debug-> add new server ("three dots" button) 192.168.0.1:9000 and fix mapping:
    - htdocs == /var/www/html; 
    - index.php == /var/www/html/www
- session key = "phpstorm"
 
- intro and more info at  [jetbrains](https://confluence.jetbrains.com/display/PhpStorm/Zero-configuration+Web+Application+Debugging+with+Xdebug+and+PhpStorm) 
               [zdrojak.cz](https://www.zdrojak.cz/clanky/jak-byt-produktivni-v-phpstormu-cast-3/)
              [blueweb.sk](https://www.slideshare.net/blueweb_sk/akademia-x-debug)
               [forum.nette](https://forum.nette.org/cs/23891-xdebug-pouziti-u-prezenteru)
