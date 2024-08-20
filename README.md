# Symfony 6 ToDo API

This repository contains a ToDo API built with Symfony 6. The API allows users to manage tasks, including creating, retrieving, updating, and deleting tasks.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [API Endpoints](#api-endpoints)
- [Running Tests](#running-tests)
- [Contributing](#contributing)
- [License](#license)

## Requirements

Before setting up the project, ensure your environment meets the following requirements:

- **PHP**: Version 8.1 or higher
- **Composer**: Version 2.0 or higher
- **Symfony CLI**: Optional but recommended for local development
- **PostgreSQL**: Version 16 or higher (if not using Docker)
- **Docker**: Version 20.10 or higher (if using Docker)
- **Docker Compose**: Version 1.28 or higher (if using Docker)

### Recommended Extensions for PHP

Ensure the following PHP extensions are enabled:

- `pdo_pgsql` (for PostgreSQL database connection)
- `intl` (for internationalization support)
- `mbstring` (for string handling)
- `openssl` (for security and encryption)
- `json` (for JSON data handling)
- `xml` (for XML parsing)

## Installation

To get started with this project, you need to have PHP, Composer, and Symfony CLI installed on your machine.

### Clone the Repository

```bash
git clone https://github.com/pusachev/todo-list.git
cd todo-list
```

### Install Dependencies
Run the following command to install the PHP dependencies:

```bash
composer install
```

## Configuration

### Configure Environment Variables

Copy the .env file to .env.local and configure the database URL and other environment variables as needed:

```bash
cp .env .env.local
```

Update the DATABASE_URL in the .env.local file:

```dotenv
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=16&charset=utf8"
```
### Generate certificates

Generate secret key pair for JWT 

```bash
php bin/console lexik:jwt:generate-keypair
```

## Database Setup

### Create the database and run the migrations:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

If you need to truncate all tables in the database (use with caution):

```bash
php bin/console app:database:truncate
```

## Running the Application

You can start the Symfony development server by running:

```bash
symfony server:start
```

```bash
docker-compose up -d
```

The application will be accessible at http://127.0.0.1:8000.

## API Endpoints

**Here are the main API endpoints available in this ToDo application:**

- Register a User: POST /api/register
- Login User: POST /api/login
- Create a Task: POST /api/tasks
- Get All Tasks: GET /api/tasks
- Get a Single Task: GET /api/tasks/{id}
- Update a Task: PUT /api/tasks/{id}
- Delete a Task: DELETE /api/tasks/{id}

### Example Requests

#### Authentication 

**Register**

```bash
curl -X POST http://127.0.0.1:8000/api/register -H "Content-Type: application/json" -d '{"email": "user@example.com", "password": "password"}'
```

**Login**

```bash
curl -X POST http://127.0.0.1:8000/api/login -H "Content-Type: application/json" -d '{"email": "user@example.com", "password": "password"}'
```

#### Task Endpoint

**Create a Task**

```bash
curl -X POST http://localhost:8000/api/tasks -H "Content-Type: application/json" -H "Authorization: Bearer {JWT_token}" -d '{"title": "New Task", "description": "Task Description"}'
```
**Get All User Tasks**

```bash
curl -X GET http://localhost:8000/api/tasks -H "Content-Type: application/json" -H "Authorization: Bearer {JWT_token}"
```

**Get a Single Task**

```bash
curl -X GET http://localhost:8000/api/tasks/{task_id} -H "Content-Type: application/json" -H "Authorization: Bearer {JWT_token}"
```

**Update a Task**

```bash
curl -X PUT http://localhost:8000/api/tasks/{task_id} -H "Content-Type: application/json" -H "Authorization: Bearer {JWT_token}" -d '{"title": "Updated Title", "description": "Updated Description"}'
```

**Delete a Task**

```bash
curl -X DELETE http://localhost:8000/api/tasks/{task_id} -H "Content-Type: application/json" -H "Authorization: Bearer {JWT_token}"
```

## Running Tests

This project includes unit and functional tests.

### Prepare the Test Database

Before running the tests, create a test database:

```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
```

### Run the Tests

Run the tests using PHPUnit:

```bash
php bin/phpunit
```

### Test Database Isolation
The tests are configured to run with database isolation to ensure a clean state for each test.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-source and available under the MIT License.
