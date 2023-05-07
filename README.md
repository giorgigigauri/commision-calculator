# Installation
To get started, first clone the repository to your local machine:
````
composer install
````
# Configuration
Before running the application, you need to configure your environment variables. Create a copy of the .env.example file and rename it to .env:
````
cp .env.example .env
````

Usage
Once you've configured the environment variables, you can run the Laravel command by running the following command:
````
php artisan commission:calculate input.csv
````

# Testing
To run the tests for the application, you can use the following command:
````
php artisan test
````
This will run all of the tests in the tests directory using PHPUnit.

# Coding Standards
This project uses the PSR-12 coding standard. To ensure that your code adheres to this standard, you can use the following command:
````
./vendor/bin/pint --preset psr12
````
This will check all of the PHP files in the application directory and report any coding standard violations.

