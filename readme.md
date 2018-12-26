# Mutants API

[![codecov](https://codecov.io/gh/sostenesgomes/mutants/branch/master/graph/badge.svg)](https://codecov.io/gh/sostenesgomes/mutants)

Mutants API is an api to check if a dna is from a mutant and offer statistics about this. 

This API was builded with [Lumen](https://lumen.laravel.com/) Framework PHP.

## Prerequisites
- PHP 7.2 or later
- Composer
- Database like (mysql, postgres, oracle, sqlite, etc...)

## How to execute in local environment

#### Step 1
Clone this repo
```
git clone https://github.com/sostenesgomes/mutants.git
```

#### Step 2
Enter directory
```
cd mutants
```

#### Step 3
Install dependencies with composer
```
composer install
```

#### Step 4
Create the .env file
```
cp .env-examle .env
```

#### Step 5
Enter your database settings on .env file
```
-- Example

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

#### Step 6
Run migrations
```
php artisan migrate
```

#### Step 7
Start the php server
```
cd public

php -S localhost:8000
```

## How to execute tests

For run tests, in root project folder, execute
```
vendor/bin/phpunit
```

## Services

```
POST /mutants

{
"dna":["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]
}
```

```
GET /stats
```