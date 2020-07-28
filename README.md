# Entropy

This repo contains a music streaming system built from scratch.

### Installation

- Install [Apache](https://httpd.apache.org/).
- Install [MySQL](https://www.mysql.com/downloads/).
- Install [phpMyAdmin](https://www.phpmyadmin.net/).
- Clone the repo inside [Apache's DocumentRoot](https://httpd.apache.org/docs/2.4/urlmapping.html) specified in the Apache configuration file.
- Start Apache.
- Start MySQL.

<br>

## Run with Docker

### Installation
Requirements
- You need to have [**Docker**](https://docs.docker.com/get-docker/) installed


### Run

Run in `root` folder,
~~~~
docker-compose build && docker-compose up -d
~~~~

## Access
- Entropy-music-streaming service, <br> [http://localhost:80](http://localhost:80)

- Access phpMyAdmin service, <br> [http://localhost:8080](http://localhost:8080)

<br>

- Run `mysql` client,
~~~~
docker-compose exec db mysql -u root -p
~~~~

- Login to the `Apache container`,
~~~~
docker exec -it <container_name> /bin/bash
~~~~

<br>

## Personalize content
- Add your own sound files in the music folder.
- Add the artwork images in the artwork folder.
- Update the Database accordingly.