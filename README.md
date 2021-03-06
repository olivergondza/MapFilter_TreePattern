README
======


MapFilter
---------

MapFilter/TreePattern is default pattern implementation for MapFilter.


License
-------

LGPL

MapFilter/TreePattern is free software: you can redistribute it and/or modify it
under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at
your option) any later version.

MapFilter/TreePattern is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
License for more details.

You should have received a copy of the GNU Lesser General Public License
along with MapFilter/TreePattern.  If not, see <http://www.gnu.org/licenses/>.


Author
------

Oliver Gondža (324706@mail.muni.cz)


Manual
------

MapFilter/TreePattern uses doxygen utility to create HTML reference manual dynamically. 
There are three different initialization files to generate user, programmer
and internal documentation.  Programmer documentation contains full
MapFilter/TreePattern package documentation.  Comprehensive documentation including
supporting classes can be found in doc/internal/ directory.

Use:


$ cd docs/
$ doxygen doxygen/user.ini

    To generate doc/user/html/


$ cd docs/
$ doxygen doxygen/programmer.ini

    To generate doc/programmer/html/


$ cd docs/
$ doxygen doxygen/internal.ini

    To generate doc/internal/html/

An on-line documentation can be found at
http://olivergondza.github.com/MapFilter_TreePattern/.

phpdoc can be used as well:

$ cd docs/
$ phpdoc -c phpdoc/user.ini

    To generate doc/user/phpdoc/


$ cd docs/
$ phpdoc -c phpdoc/programmer.ini

    To generate doc/programmer/phpdoc/


$ cd docs/
$ phpdoc -c phpdoc/internal.ini

    To generate doc/internal/phpdoc/


Download
--------

Current version:

$ git clone git://github.com/olivergondza/MapFilter_TreePattern

or Zip

http://github.com/olivergondza/MapFilter_TreePattern/zipball/master

or Tar

http://github.com/olivergondza/MapFilter_TreePattern/tarball/master
