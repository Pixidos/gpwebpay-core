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

SPHINXOPTS    =
SPHINXBUILD   = sphinx-build
SOURCEDIR     = docs
BUILDDIR      = docs/_build


ALLSPHINXOPTS   = -d $(BUILDDIR)/doctrees $(PAPEROPT_$(PAPER)) $(SPHINXOPTS) docs
# the i18n builder cannot share the environment and doctrees with the others
I18NSPHINXOPTS  = $(PAPEROPT_$(PAPER)) $(SPHINXOPTS) docs

clean:
	rm -rf $(BUILDDIR)/*

html:
	$(SPHINXBUILD) -b html $(ALLSPHINXOPTS) $(BUILDDIR)/html
	@echo
	@echo "Build finished. The HTML pages are in $(BUILDDIR)/html."

changes:
	$(SPHINXBUILD) -b changes $(ALLSPHINXOPTS) $(BUILDDIR)/changes
	@echo
	@echo "The overview file is in $(BUILDDIR)/changes."

linkcheck:
	$(SPHINXBUILD) -b linkcheck $(ALLSPHINXOPTS) $(BUILDDIR)/linkcheck
	@echo
	@echo "Link check complete; look for any errors in the above output " \
	      "or in $(BUILDDIR)/linkcheck/output.txt."
