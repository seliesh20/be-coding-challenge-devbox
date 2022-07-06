# L1 Challenge Devbox

## Tasks

- 1. Created console command 'app:log-sync <filename>'. It will check the file in '/var/log' folder
- 2. Created API for return the count for the endpoing '/count'
- 3. Modified composer.json to migrate the database 

## Setup

- Dockerfile & Docker-compose setup with PHP8.1 and MySQL
- Symfony 5.4 installation with a /healthz endpoint and a test for it
- After the image is started the app will run on port 9002 on localhost. You can try the existing
  endpoint: http://localhost:9002/healthz
- The default database is called `database` and the username and password are `root` and `root`
  respectively
- Makefile with some basic commands

## Installation

```
  make run && make install
```

## Run commands inside the container

```
  make enter
```

## Run tests

```
  make test
```
