test:
	php vendor/bin/phpunit tests -c tests/phpunit.xml

prepare:
	composer install
	php bin/console do:mig:mig -n
	php bin/console do:fix:load -n
