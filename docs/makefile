USER_INI = user.ini
PROGRAMMER_INI = programmer.ini
INTERNAL_INI = internal.ini

PHPDOC = phpdoc -c ./phpdoc/

DOXYGEN = doxygen ./doxygen/

.PHONY: doxygen phpdoc

all: doxygen phpdoc

clean:
	rm -rf ./doc/user/* ./doc/programmer/* ./doc/internal/*
	
doxygen:
	$(DOXYGEN)$(USER_INI) > /dev/null
	$(DOXYGEN)$(PROGRAMMER_INI) > /dev/null
	$(DOXYGEN)$(INTERNAL_INI) > /dev/null

phpdoc:
	$(PHPDOC)$(USER_INI) > /dev/null
	$(PHPDOC)$(PROGRAMMER_INI) > /dev/null
	$(PHPDOC)$(INTERNAL_INI) > /dev/null
