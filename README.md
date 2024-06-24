<h1 align="center">
  PHP-Docker-WebApps
</h1>

## Overview

This is a full-stack web-app using PHP, MySQL, and Docker. I made this to display all of my past PHP university assignments into one web-app and host it on a DigitalOcean Droplet (VM) to see it online.

## How to Run Docker

Install Docker Desktop on your computer.

Clone this git repository into a folder.

To run this code, run the command: `docker-compose up` from the terminal shell within the directory of this project after cloning it.

If you get the error: `Fatal error: Uncaught Error: Call to undefined function mysqli_connect() in /var/www/html/index.php:3 Stack trace: #0 {main} thrown in /var/www/html/index.php on line 3`

Open Docker Desktop and in the interactive terminal with your docker container that's running the `www` service and run the command: `docker-php-ext-install mysqli && docker-php-ext-enable mysqli && apachectl restart`

Open localhost/index.php on your browser to view the web-app on your machine

## Deploying on DigitalOcean VM

1. Create a new droplet on DigitalOcean
2. Follow this tutorial: https://www.youtube.com/playlist?list=PL4apNHBRJVlQ56Pj3BS2CfxhavTAV3MLd
