# Project Setup

## Prerequisites
- Docker
- Docker Compose

## Getting Started

### Build and Run Docker Containers
To build and start the Docker containers, run the following command:
```sh
docker-compose up --build
```

### To access the phpunit container, use the following command:
```sh
docker exec -it unittest /bin/bash
```

### To run the tests, use the following command:
```sh
vendor/bin/phpunit --coverage-html=coverage
```
