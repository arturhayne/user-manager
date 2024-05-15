# User Manager
This system was made to validate user value fields based in the population fields following the structure below:

![screenshot](user-manager.png)

The rules to validate the user values are in the population fields.
[More info](support/php-tech-test.pdf)

## Changes
It was added a new field in population_fields `is_unique_across_population` to validate if the field is unique across population.

Besides that, `email` and `employeeid` became `is_unique_across_population = true`.

Theses changes could be seen in [database/unique-across-population.sql](database/unique-across-population.sql).

The property of field uniqueness (isunique) within the population is still working as before.

## Flow
index -> UserValuesController -> UserValuesCommandHandler -> UserValuesValidationService -> Validator -> Repository

- UserValuesCommandHandler uses UserRepository to retrieve the use information with population fields.
- UserValuesValidationService uses Validator to check if fields are unique across population and within population.

## Structure
The system is architectured following the Application, Domain, and Infrastructure structure, facilitating seamless logic migration to various system architectures. For instance, transitioning database structures to leverage an ORM or a different database system merely requires integrating a new implementation of the repositories. Similarly, the controllers are adaptable, allowing for alternative handling structures, such as encapsulation within a Composer package.

This is system was made without any framework and it is using the [Container](app/Container.php) class to provide dependecy injection, following SOLID principles.

## Validation

The core of validations are made in [Validator](src/Domain/Validator.php).

For any further type or additional validation, changes needs to be made there.

## Usage
1. docker-compose up -d
2. docker-compose exec web bash
3. composer install (inside the container)
4. php database/create-tables.php
5. php database/seed-tables.php
6. php database/update-population-fields.php (added new field is_unique_across_population and update email and emploeeid fields)

## Run
php -S 0.0.0.0:8070 (inside the container)

## Usage

`POST http://localhost:8070/user/1/validate-values` (where 1 is the user id)
Body Request:
```
{
    "employeeid": "A00513",
    "fname": "Jhon",
    "lname": "Doe",
    "bdate": "1994-01-23",
    "bplace": "Carignan, QC, Canada",
    "email": "jdoe@example.com"
}
```
Response:
```
{
    "status": 400,
    "error": {
        "employeeid": {
            "isUniqueAcrossPopulation": "Employeeid is not unique across population."
        },
        "email": {
            "isUniqueAcrossPopulation": "Email is not unique across population."
        }
    }
}
```

Curl
```
curl -X POST \
  http://localhost:8070/user/1/validate-values \
  -H 'Content-Type: application/json' \
  -d '{
    "employeeid": "A00513",
    "fname": "Jhon",
    "lname": "Doe",
    "bdate": "1994-01-23",
    "bplace": "Carignan, QC, Canada",
    "email": "jdoe@example.com"
  }'
```

### Code style
Using [oskarstark](https://github.com/OskarStark/php-cs-fixer-ga) to autofix phpcs
```
docker run --rm -it -w=/app -v ${PWD}:/app oskarstark/php-cs-fixer-ga:latest
```

### Tests
```
/var/www/html# vendor/bin/phpunit --testdox
PHPUnit 10.5.20 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.18
Configuration: /var/www/html/phpunit.xml

..........                                                        10 / 10 (100%)

Time: 00:00.138, Memory: 8.00 MB

User Values Command Handler (Tests\System\UserValuesCommandHandler)
 ✔ Employeeid should be unique across population
 ✔ Email should be unique across population
 ✔ Sould validate success

User Values Validation Service (Tests\Unit\UserValuesValidationService)
 ✔ Should validate unique fields
 ✔ Should pass non unique fields
 ✔ Should validate unique across population
 ✔ Should pass non unique across population
 ✔ Without unique validation
 ✔ Email validation
 ✔ Date validation

OK (10 tests, 14 assertions)
```
Note: In a real system test scenario we should use a test database and populate it according to the test.
The system tests are using the data provided in \database\data.sql script. 

## Debug
Update `xdebug.start_with_request=no` to yes in xdebug.ini
