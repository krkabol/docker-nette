## My Docker Cheatsheet
 
#### run docker as non-sudo user "petr"
```bash
   sudo usermod -aG docker petr
```

#### make host accesible from containers on 192.168.0.1
1) create network, use it in docker-compose.yml as external
```bash
docker network create -d bridge --subnet 192.168.0.0/24 --gateway 192.168.0.1 dockernet
```


#### basic DOCKER commands
- list all running  containers  ```docker ps```
- list stats for running containers  ```docker stats $(docker ps --format={{.Names}})```
- list all runnig and stopped containers```docker ps -a```
- list all images ```docker images -a```
- delete all images ```docker rmi $(docker images -q)```
- stop running container "xxx" ```docker stop xxx```
- stop all running containers  ```docker stop $(docker ps -a -q)```
- delete stopped containers ```docker container prune```
- connect to bash of running container "xxx"  ```sudo docker exec -i -t xxx /bin/bash```
- check from container wheather is host accesible (typically PostgreSQL on host) ``` nmap -v -p 5432 192.168.0.1```
- copy file form cintainer ```docker cp <containerId>:/file/path/within/container /host/path/target```

#### basic DOCKER_COMPOSE commnands
- start group of containers ```docker-compose up -d```
- selective stop/rm ```docker-compose stop nginx-proxy``` | ```docker-compose rm nginx-proxy```
- run container in compose with actual user - <https://stackoverflow.com/questions/36551510/how-to-pass-host-user-to-dockerfile-when-using-docker-compose>
