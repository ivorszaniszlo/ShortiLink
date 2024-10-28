# ShortiLink App

## Table of Contents

- [General Information](#general-information)
- [Description](#description)
- [Technologies](#technologies)
- [Setup](#setup)
  - [Docker Setup](#docker-setup)
  - [Node.js Setup](#node.js-setup)
  - [Database Setup](#database-setup)
- [Running](#running)
  - [Backend Running](#backend-running)
  - [Testing](#testing)
- [Status](#status)
- [Created By](#created-by)

## General Information

The ShortiLink application is a Laravel 11-based URL shortening system that allows users to easily shorten long URLs, similar to bit.ly. With this application, users can quickly generate short URLs and share them across different platforms. Laravel 11 introduces several new features, including a minimal application structure, SQLite as the default database, health routing, and enhanced queue interaction testing.

## Description

With ShortiLink, you can easily shorten long URLs. The backend is built on Laravel 11, while the frontend utilizes Alpine.js and Livewire to provide an interactive user experience.

## Screenshot



## Technologies

- Laravel 11
- PHP 8.2
- MySQL
- Alpine.js and Livewire
- Docker
- Adminer (Database management)
- PHP Debug Bar
- PHPUnit

## Setup

### Clone the Repository

```bash
git clone https://github.com/ivorszaniszlo/ShortiLink.git
```

### Backend Setup

Navigate to the project directory and install backend dependencies:

```bash
cd shortilink
docker-compose exec app composer install
```

### Environment Setup

Set up environment variables:

```bash
cp .env.example .env
docker-compose exec app php artisan key:generate
```

### Database Setup

Set up the database and run migrations:

```bash
docker-compose exec app php artisan migrate --seed
```

To access the database, you can use the Adminer interface at `http://localhost:8081`.

### Install PHP Debug Bar

```bash
docker-compose exec app composer require barryvdh/laravel-debugbar --dev
```

### Docker Setup

Build and run the Docker containers:

```bash
docker-compose up --build
```

### Node.js Setup

Install Node.js dependencies for the frontend:

```bash
docker-compose exec app npm install
```

## Running

### Backend Running

Serve the Laravel backend application:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

The application will be available at `http://localhost:8000`. Note that before serving the Laravel application, make sure to run the migrations.

## Testing

To run the tests, use the following command:

```bash
docker-compose exec app php artisan test
```

This command will run all unit and functional tests of the application, ensuring proper functionality.

If you need to refresh the database before testing, use the following command:

```bash
php artisan migrate:refresh --seed
```

## Status

Active

## Created By

2024
