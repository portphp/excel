# when you run 'make' alone, run the 'css' rule (at the
# bottom of this makefile)
all: phpcs phpunit

# .PHONY is a special command, that allows you not to
# require physical files as the target (allowing us to
# use the 'all' rule as the default target).
.PHONY: all

# Begin Cleaning Targets
clean:
	git clean -Xdf
# End Cleaning Targets

# Begin Test Targets
test: test.all
test.all: phpcs phpunit phpstan
test.strict: phpcs.warnings phpunit phpstan

phpcs: phpcs.errors

phpcbf: phpcbf.errors

phpcs.warnings: composer
	vendor/bin/phpcs -p --colors --standard=phpcs.xml src/
	vendor/bin/phpcs -p --colors --standard=phpcs.xml tests/

phpcbf.warnings: composer
	vendor/bin/phpcbf -p --colors --standard=phpcs.xml src/
	vendor/bin/phpcbf -p --colors --standard=phpcs.xml tests/

phpcs.errors: composer
	vendor/bin/phpcs -p --colors --warning-severity=0 --standard=phpcs.xml src/
	vendor/bin/phpcs -p --colors --warning-severity=0 --standard=phpcs.xml tests/

phpcbf.errors: composer
	vendor/bin/phpcbf -p --colors --warning-severity=0 --standard=phpcs.xml src/
	vendor/bin/phpcbf -p --colors --warning-severity=0 --standard=phpcs.xml tests/

# https://github.com/phpstan/phpstan#rule-levels
phpstan: phpstan.default
phpstan.default: phpstan.0
phpstan.0: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 0 -a vendor/autoload.php src
phpstan.1: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 1 -a vendor/autoload.php src
phpstan.2: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 2 -a vendor/autoload.php src
phpstan.3: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 3 -a vendor/autoload.php src
phpstan.4: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 4 -a vendor/autoload.php src
phpstan.5: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 5 -a vendor/autoload.php src
phpstan.6: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 6 -a vendor/autoload.php src
phpstan.7: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level 7 -a vendor/autoload.php src
phpstan.max: composer
	vendor/bin/phpstan analyze -c phpstan.neon --level max -a vendor/autoload.php src

phpunit: composer
	vendor/bin/phpunit

phpunit.coverage: composer
	vendor/bin/phpunit --coverage-html coverageReport
# End Test Targets

# Begin Prepare Targets
composer:
	composer install
# End Prepare Targets
