<?php

error_reporting ( ( E_ALL | E_STRICT ) );

set_include_path (
    dirname ( dirname ( __FILE__ ) )
    . PATH_SEPARATOR . get_include_path ()
);

define ( 'PHP_TREEPATTERN_DIR', 'PHP/MapFilter/TreePattern' );
define ( 'PHP_TREEPATTERN_CLASS', 'PHP/MapFilter/TreePattern.php' );

define ( 'PHP_TREEPATTERN_TEST_DIR', dirname ( __FILE__ ) );

require_once 'PHP/MapFilter.php';

class MapFilter_Test_Sources {
  const LOCATION = '/sources/location.xml';
  const LOGIN = '/sources/login.xml';
  const COFFEE_MAKER = '/sources/coffee_maker.xml';
  const CAT = '/sources/cat.xml';
  const ACTION = '/sources/action.xml';
  const FILTER = '/sources/filter.xml';
  const DURATION = '/sources/duration.xml';
  const GENERATOR = '/sources/generator.xml';
  const DIRECTION = '/sources/direction.xml';
  const PATHWAY = '/sources/pathway.xml';
  const PARSEINIFILE_INI = '/sources/parse_ini_file.ini';
  const PARSEINIFILE_XML = '/sources/parse_ini_file.xml';
}
