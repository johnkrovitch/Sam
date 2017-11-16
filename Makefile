all: install

install:
	composer install

tests:
	bin/phpunit
