# Tomo-TDD
Laravel unit testing competency test

## Getting Started
This application can be installed on local server and online server with these specifications :

#### Server Requirements
1. PHP >= 7.1.3 (and meet [Laravel 5.8 server requirements](https://laravel.com/docs/5.8#server-requirements)),
2. MySQL or MariaDB database,
3. SQlite (for automated testing).

#### Installation Steps

1. Clone the repo : `git@github.com:prihutomo/tomo-tdd.git`
2. `$ cd tomo-tdd`
3. `$ composer install`
4. `$ cp .env.example .env`
5. `$ php artisan key:generate`
6. Create new MySQL database for this application  
(with simple command: `$ mysqladmin -urootuser -p create tomo_tdd_db`)
7. Set database credentials on `.env` file
8. `$ php artisan migrate`
9. `$ php artisan serve`
10. Register new account.
