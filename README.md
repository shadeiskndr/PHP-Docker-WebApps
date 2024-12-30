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

1. Install Node.js dependencies:

   ```bash
   npm install

   ```

2. Build Tailwind CSS:

- One-time build:

  ```bash
  npm run build

  ```

- Development watch mode:

  ```bash
  npm run watch
  ```

## Deploying on DigitalOcean VM

1. Create a new droplet on DigitalOcean.

2. Install Docker and Docker Compose:

   ```bash
   sudo apt update
   sudo apt install docker.io
   sudo systemctl start docker
   sudo systemctl enable docker
   sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
   sudo chmod +x /usr/local/bin/docker-compose
   ```

3. Configure firewall rules:

   ```bash
   sudo ufw allow 22/tcp
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw enable
   ```

4. Git clone this repository into the droplet.

5. Run the command docker-compose up from the terminal shell within the directory of this project.

6. For domain and SSL setup:

- Add DNS A record pointing to your droplet's IP
- Install Certbot: sudo apt install certbot python3-certbot-apache
- Get SSL certificate: sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

7. Access container shell:

```bash
 docker ps # List containers
 docker exec -it CONTAINER_ID bash # Replace CONTAINER_ID with your container ID
```

# OR

```bash
docker-compose exec www bash # Using service name
```
