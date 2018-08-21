docker-nette 
---

Set of Docker containers optimized to run Nette web application in develop mode.

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
git clone https://github.com/krkabol/docker-nette-container.git 
```

- fork [sample app](https://github.com/krkabol/docker-nette-app) and clone your copy
```bash
git clone https://github.com/krkabol/docker-nette-app.git docker-nette-app
```
- AND make a symbolic link 
```bash
ln -s /home/jake/docker-nette-app /home/jake/docker-nette-container/application/nette-app
```

- now start by ```docker-compose up -d``` and follow the "sample app" instructions
- after testing replace the sample app with your own...
  
## Usage
### Links
```
127.0.0.1:80   app
127.0.0.1:8080   adminer
127.0.0.1:5601 	kibana
```

### Logging
Logs of all containers as well as of the sample app are saved by Fluent to the Elasticsearch container. You can access them by Kibana at <localhost:5601>, as prefix use  ```nette-*``` (set in ./fluentd/conf/fluent.conf -> ```logstash_prefix nette```)
- more info at <http://docs.fluentd.org/v0.12/articles/docker-logging-efk-compose>, <http://docs.fluentd.org/v0.12/articles/php>, [linking Nette to Fluent](https://filip-prochazka.com/blog/newrelic-monitoring-aplikace-na-nette-frameworku)


### XDebug
Xdebug is running in the PHP container, to use it by PHPStorm you need:
- use default settings of PHPStorm like port 9000 
- PHPStorm->Run->EditConfigurations->"plus" button->PHP Remote Debug-> select server, "three dots" button -> mapping - htdocs == /var/www/html; index.php == /var/www/html/www
- session key = "phpstorm"
 
- intro and more info at  [jetbrains](https://confluence.jetbrains.com/display/PhpStorm/Zero-configuration+Web+Application+Debugging+with+Xdebug+and+PhpStorm) 
               [zdrojak.cz](https://www.zdrojak.cz/clanky/jak-byt-produktivni-v-phpstormu-cast-3/)
              [blueweb.sk](https://www.slideshare.net/blueweb_sk/akademia-x-debug)
               [forum.nette](https://forum.nette.org/cs/23891-xdebug-pouziti-u-prezenteru)
 
## Tips
- for sharing nginx proxy by multiple docker-compose entities check <https://github.com/jwilder/nginx-proxy/issues/644>

# docker-nette-app
Sample Nette application to run inside <https://github.com/krkabol/docker-nette-container>.

## Before Install
1) Clone related [repository](https://github.com/krkabol/docker-nette-container) (we'll call it CONTAINERS)
1) Make symlink of this repository (let's call it APP) to the path ```CONTAINERS./application/nette-app ``` 
1) Follow install+run instructions in CONTAINERS
1) When you have ready CONTAINERS, come back and follow this install recipe...
  
## Install

- ```APP./ chmod 777 -R htdocs/temp htdocs/log```
- install PHP dependencies by Composer ```CONTAINERS ./composer.sh install```
- install Node packages ```CONTAINERS ./npm.sh``` 
- install dependencies by Bower ```CONTAINERS ./bower.sh install``` 
- run Grunt default task by ```CONTAINERS ./grunt.sh```

- create file ```APP./htdocs/app/config/config.local.neon``` as a copy of ```APP./htdocs/app/config/config.local.neon.sample```
- connect to PHP container and prepare base schema of database:
```bash
    docker exec -i -t nette_aplication /bin/bash
    php www/index.php orm:schema-tool:create && exit 
``` 
- fill database with test data:
    - enjoy PHP Adminer (one of already running container) on this [link](http://localhost:8080/?pgsql=postgres&username=nette&db=nette&ns=main):         
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

### XDebug
Xdebug is running in the PHP container, to use it by PHPStorm you need:
- use default settings of PHPStorm like port 9000 
- PHPStorm->Run->EditConfigurations->"plus" button->PHP Remote Debug-> select server, "three dots" button -> mapping - htdocs == /var/www/html; index.php == /var/www/html/www/index.php
- session key = "phpstorm"        