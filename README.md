<h1 align="center">
  PHP-Docker-WebApps
</h1>

## Overview

This is a simple PHP, MySQL, Apache web application that has been dockerized into one container by using Docker Compose.

## How to Run on Localhost

1. Install Docker Desktop on your computer.

2. Clone this git repository into a folder.

3. To run this code, run the command: `docker-compose up` from the terminal shell within the directory of this project after cloning it.

4. Open localhost/index.php on your browser to view the web-app on your machine.

## Development Setup

### Tailwind CSS Setup

1. Install Tailwind CSS dependency (Need Node.js installed):

   ```bash
   npm install
   ```

2. Build Tailwind CSS:

- Development watch mode:

  ```bash
  npm run dev
  ```

- One-time build:

  ```bash
  npm run build
  ```

### Rebuild and Restart Containers

- docker-compose down

- docker-compose build

- docker-compose up -d

## Simulate deployment (using Ngrok)

1. Install ngrok via Docker with the following command:

   ```bash
   docker pull ngrok/ngrok
   ```

2. Sign up or log in to ngrok.com and get your auth token.

3. Put your app online at an ephemeral domain forwarding to your upstream service. Start the project container and also run the ngrok container by:

   ```bash
   docker run --net=host -it -e NGROK_AUTHTOKEN=<YOUR_NGROK_AUTH_TOKEN> ngrok/ngrok:latest http 80
   ```

4. Once done, you will get an ephemeral domain link to see your app in the terminal.
