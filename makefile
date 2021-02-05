fix:
	vendor/bin/phpcbf

testPhpCs:
	@printf "\e[103;30m******************************             PhpCs             ******************************\e[0m\n"
	vendor/bin/phpcs -p

testPhpStan:
	@printf "\e[103;30m******************************            PhpStan            ******************************\e[0m\n"
	@printf "Running stan...\n"
	vendor/bin/phpstan analyse

testPhpUnit:
	@printf "\e[103;30m******************************            PhpUnit            ******************************\e[0m\n"
	vendor/bin/phpunit

test: testPhpCs testPhpStan testPhpUnit
