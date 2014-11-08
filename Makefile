test:
	./vendor/bin/phpunit -c ./

coverage:
	./vendor/bin/phpunit -c ./ --coverage-html=build/coverage
