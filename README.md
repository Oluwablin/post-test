## Post-test

## Project Description

This application is for post apis.

## Project Setup

### Cloning the GitHub Repository

Clone the repository to your local machine by running the terminal command below.

```bash
git clone https://github.com/Oluwablin/post-test
```

### Setup Database

Create a MySQL database and note down the required connection parameters. (DB Host, Username, Password, Name)

### Install Composer Dependencies

Navigate to the project root directory via terminal and run the following command.

```bash
composer install
```

### Create a copy of your .env file

Run the following command

```bash
cp .env.example .env
```

This should create an exact copy of the .env.example file. Name the newly created file .env and update it with your local environment variables (database connection info and others).

### Create a copy of your .env file

Then run the following command to set env for your unit tests if it is not available.

```bash
cp .env .env.testing
```

Then run the following command to run your unit tests.

```bash
php artisan test
```

### Postman Documentation

https://documenter.getpostman.com/view/9649360/2sAYQakWfY