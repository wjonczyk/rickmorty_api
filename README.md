# Rick & Morty Characters RESTApi

This application allows to fetch characters from https://rickandmortyapi.com/ to supplied MariaDB database
using console command and provides endpoint returning imported resources by name.
It can be run locally utilising Docker containers.<br>
Provided docker-compose.yml file contains definitions for custom php, mariadb and nginx containers. 

## Prerequisites
Make sure that **docker** and **docker-compose** are installed.

## Installation and starting app with docker-compose

### Step 1.
Use git to clone this repository on Your system

### Step 2.
Using commandline terminal cd into cloned projects root `rickmorty_api` directory.<br>
All subsequent commands should be executed from this location

### Step 3.
#### Start application by running
```bash
docker-compose up -d
```
Docker should pull necessary images, build containers and start them in detached mode<br>
You can also omit `-d` and containers will run in current terminal. If so, open another terminal and continue
To stop application at any moment run
```bash
docker-compose down
```
but to continue with installation process, leave it running for now.

### Step 4.
#### Install required dependencies using composer
We use composer container to install required packages using
```bash
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install
```
Wait until composer script finishes.

### Step 5. (optional)
This step is optional. Database should be created on mysql container startup, but in case<br>
somehow it didn't, or You deleted it and next step throws an error, db can be created<br>
by this script **php ./bin/console doctrine:database:create**
You can also inspect database on localhost:3306 and credentials from docker-compose.yaml
#### Database Create First after docker-compose up<br>
(only if the database wasn't created on container startup!)
```bash
docker exec -it rickmorty_api-php-1 php ./bin/console doctrine:database:create
```

### Step 6.
To create tables in database run this doctrine migrations script
#### Create required tables using migration
```bash
docker exec -it rickmorty_api-php-1 php ./bin/console doctrine:migrations:migrate
```

### Step 7.
To fetch characters data from Rick&Morty API run this console command and wait for it to finish.<br>
You should see message with a count of how many characters were imported.<br>
Every time it is executed the table is emptied and fresh data is populated. This application depends on mentioned data.<br>
#### Run Console Command
```bash
docker exec -it rickmorty_api-php-1 php ./bin/console app:consume-characters-api
```

### Step 8.
Once all above is done, You should be able to visit application in browser http://127.0.0.1/
There is an enpoint with documentation http://127.0.0.1/api <br>
and main endpoint returning characters data by 'name' http://127.0.0.1/api/characters/{name}
It can be tested with postman or curl requests, for ex.:
```
curl -X 'GET' \
'http://127.0.0.1/api/characters/Morty%20Smith' \
-H 'accept: application/json'
```

### Step 9.
When done using application it can be stopped by running this command.<br>
It removes containers and networks created by `docker-compose up -d`
#### Stop application by running
```bash
docker-compose down
```
You can delete the containers along with their associated docker volumes using the `docker-compose down -v` command.<br>
But doing so results in losing all data previously stored in the database


### Additional 1
This command clears Symfony applications cache
#### Clears Cache
```bash
docker exec -it rickmorty_api-php-1 php ./bin/console cache:clear
```


