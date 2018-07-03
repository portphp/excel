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
phpcs: phpcs.errors

phpcbf: phpcbf.errors

phpcs.warnings: composer
	vendor/bin/phpcs -p --colors --standard=phpcs.xml src/

phpcbf.warnings: composer
	vendor/bin/phpcbf -p --colors --standard=phpcs.xml src/

phpcs.errors: composer
	vendor/bin/phpcs -p --colors --warning-severity=0 --standard=phpcs.xml src/

phpcbf.errors: composer
	vendor/bin/phpcbf -p --colors --warning-severity=0 --standard=phpcs.xml src/

phpunit: composer
	vendor/bin/phpunit
# End Test Targets

# Begin Prepare Targets
composer:
	composer install
# End Prepare Targets
