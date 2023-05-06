__# Fee-Calculator

Basic calculator for calculating fees for a given amount and term.

You will need:
- php 8.1
- composer 

To run the application:
- clone the repository
- cd into the project directory
- on MacOS it is enough to run `make prepare`,

Otherwise run following commands:

```
composer install
php bin/console do:mig:mig -n
php bin/console do:fix:load -n
```

To run the tests:
```
make test
```

or

```
php vendor/bin/phpunit tests -c tests/phpunit.xml
```

There is also an endpoint available in application, start local symfony server for example using Symfony CLI:
```
symfony serve -d
```

and send request to:

```
POST /api/fee/calculate
```

with following body:

```json
{
    "term": 12,
    "amount": 1000
}
```
