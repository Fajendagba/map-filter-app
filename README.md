# Map Filter App

## Overview
This Map Filter App is built using Laravel, and it's a map-based filtering and advanced matchmaking algorithm.

## Installation

1. **Clone the repository:**
    ```sh
    git clone https://github.com/fajendagba/map-filter-app.git
    cd map-filter-app
    ```

2. **Install dependencies:**
    ```sh
    composer install
    ```

3. **Create a `.env` file:**
    ```sh
    cp .env.example .env
    ```

4. **Generate application key:**
    ```sh
    php artisan key:generate
    ```

5. **Set up your database credentials in the `.env` file:**
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. **Run database migrations:**
    ```sh
    php artisan migrate
    ```

7. **Seed data:**
    ```sh
    php artisan db:seed
    ```

8. **Start the development server:**
    ```sh
    php artisan serve
    ```

## Requirement
To run the the app, you need MAPBOX_ACCESS_TOKEN
