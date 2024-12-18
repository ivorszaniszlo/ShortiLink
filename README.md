# ShortiLink App

## Table of Contents

- [General Information](#general-information)
- [Description](#description)
- [Technologies](#technologies)
- [Setup](#setup)
  - [Clone the Repository](#clone-the-repository)
  - [Backend Setup](#backend-setup)
  - [Environment Setup](#environment-setup)
  - [Database Setup](#database-setup)
  - [Docker Setup](#docker-setup)
  - [Node.js Setup](#nodejs-setup)
- [Running](#running)
  - [Backend Running](#backend-running)
- [API Endpoints](#api-endpoints)
- [Artisan Commands](#artisan-commands)
- [Testing](#testing)
- [Code Quality Analysis](#code-quality-analysis)
- [Status](#status)
- [Created By](#created-by)

## General Information

The ShortiLink application is a Laravel 11-based URL shortening system that enables users to easily generate short URLs, similar to bit.ly. The project leverages Laravel 11's new minimal structure and includes advanced features such as MySQL as the default database, health routing, queue interaction testing, and an enhanced base controller.

## Description

With ShortiLink, users can shorten lengthy URLs to share across different platforms. The backend is built on Laravel 11.

## Technologies

- Laravel 11
- PHP 8.2
- MySQL
- Docker
- Adminer (Database management)
- PHP Debug Bar
- PHPUnit
- Livewire
- Alpine.js
- PHPStan (for static analysis)

## Setup

### Clone the Repository

Clone the project repository to your local machine:

```bash
git clone https://github.com/ivorszaniszlo/ShortiLink.git
```

### Backend Setup

Navigate to the project directory and install backend dependencies:

```bash
cd ShortiLink
docker-compose exec app composer install
```

### Environment Setup

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
docker-compose exec app php artisan key:generate
```

### Database Setup

Run migrations and seed the database:

```bash
docker-compose exec app php artisan migrate --seed
```

The database management tool Adminer is available at `http://localhost:8081`.

Note: Ensure that the latest migration files are applied to reflect the new database schema.

### Install PHP Debug Bar

Add PHP Debug Bar to assist with debugging during development:

```bash
docker-compose exec app composer require barryvdh/laravel-debugbar --dev
```

### Docker Setup

Build and start the Docker containers:

```bash
docker-compose up --build
```

### Node.js Setup

Install the frontend dependencies:

```bash
docker-compose exec app npm install
```

## Running

### Backend Running

Serve the Laravel application:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

The application is now accessible at `http://localhost:8000`. Be sure to run migrations prior to starting the server.

## API Endpoints

The ShortiLink application provides the following API endpoints for URL shortening functionality.

### 1. `POST /new`

- **Description**: Accepts a long URL and generates a shortened URL.
- **Request Method**: `POST`
- **Request Body**:
  - `url`: Required. The long URL to be shortened.
- **Example Request**:
  ```bash
  curl -X POST http://localhost:8000/new -d "url=https://example.com"
  ```
- **Response**:
  - Returns the shortened URL in JSON format.
  - **Example**:
    ```json
    {
      "shortened_url": "/jump/e11543"
    }
    ```

### 2. `GET /jump/{code}`

- **Description**: Redirects to the original long URL based on the provided short code.
- **Request Method**: `GET`
- **Route Parameters**:
  - `code`: Required. The unique short code for the URL.
- **Example Request**:
  ```bash
  curl -L http://localhost:8000/jump/e11543
  ```
- **Response**:
  - Redirects to the original URL.
  - If the code is invalid, returns a 404 error with JSON response:
    ```json
    {
      "error": "URL not found"
    }
    ```

## Artisan Commands

The ShortiLink App provides the following Artisan commands for managing shortened URLs.

### `php artisan urls:list`

- **Description**: Lists all shortened URLs stored in the system.

- **Usage**:

  ```bash
  php artisan urls:list
  ```

- **Options**:

  - `--search=`: Optional. Filter the URLs by a specific search term found in the original URL or short code.

- **Examples**:

  - **List all shortened URLs**:

    ```bash
    php artisan urls:list
    ```

    **Output**:

    ```diff
    +----+----------------------------+------------+---------------------+
    | ID | Original URL               | Short Code | Created At          |
    +----+----------------------------+------------+---------------------+
    | 1  | https://example.com        | abc123     | 2024-04-27 12:34:56 |
    | 2  | https://another-site.org   | def456     | 2024-04-27 12:35:10 |
    +----+----------------------------+------------+---------------------+
    ```

  - **List URLs containing "example.com"**:

    ```bash
    php artisan urls:list --search=example.com
    ```

    **Output**:

    ```diff
    +----+----------------------------+------------+---------------------+
    | ID | Original URL               | Short Code | Created At          |
    +----+----------------------------+------------+---------------------+
    | 1  | https://example.com        | abc123     | 2024-04-27 12:34:56 |
    +----+----------------------------+------------+---------------------+
    ```

  - **No URLs Found**:

    If there are no shortened URLs in the system, the command will output:

    ```yaml
    No shortened URLs found in the system.
    ```

## Testing

Run all unit and functional tests with the following command:

```bash
docker-compose exec app php artisan test
```

For database refresh before testing, use:

```bash
php artisan migrate:refresh --seed
```

Note: We also use PHPStan to ensure code quality, particularly for type consistency across the application.

## Code Quality Analysis

To ensure the code maintains a high standard, Larastan and PHPInsights are used for static analysis and code quality checks.

Run Larastan analysis with the following command:

```bash
docker-compose exec app vendor/bin/phpstan analyse
```

The output of the Larastan analysis for the ShortiLink project can be seen below:

![ShortiLink Code Analysis Result](./public/shortilink-code-analysis-result.jpg)

## Status

Active

## Created By

Szaniszló Ivor, 2024
