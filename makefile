fix:
	vendor/bin/phpcbf

testPhpCs:
	@printf "\e[103;30m******************************             PhpCs             ******************************\e[0m\n"
	vendor/bin/phpcs -p

testPhpStan:
	@printf "\e[103;30m******************************            PhpStan            ******************************\e[0m\n"
	@printf "Running stan...\n"
	vendor/bin/phpstan analyse --memory-limit=1G

testPhpUnit:
	@printf "\e[103;30m******************************            PhpUnit            ******************************\e[0m\n"
	vendor/bin/phpunit

test: testPhpCs testPhpStan testPhpUnit

audit:
	@printf "\e[103;30m******************************         Composer audit        ******************************\e[0m\n"
	composer validate
	composer audit --locked

html:
	sphinx-build -b html docs docs/_build/html
