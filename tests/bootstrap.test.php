<?php

error_reporting ( ( E_ALL | E_STRICT ) );

set_include_path (
    dirname ( dirname ( __FILE__ ) )
    . PATH_SEPARATOR . get_include_path ()
);

define ( 'PHP_TREEPATTERN_TEST_DIR', dirname ( __FILE__ ) );

require_once 'PHP/MapFilter.php';

class MapFilter_Test_Sources {
  const ACTION = '/sources/action.xml';
  const CAT_NEW = '/new.sources/cat.xml';
  const CAT = '/sources/cat.xml';
  const COFFEE_MAKER_NEW = '/new.sources/coffee_maker.xml';
  const COFFEE_MAKER = '/sources/coffee_maker.xml';
  const DIRECTION_NEW = '/new.sources/direction.xml';
  const DIRECTION = '/sources/direction.xml';
  const DURATION_NEW = '/new.sources/duration.xml';
  const DURATION = '/sources/duration.xml';
  const FILTER_NEW = '/new.sources/filter.xml';
  const FILTER = '/sources/filter.xml';
  const GENERATOR = '/sources/generator.xml';
  const LOCATION_NEW = '/new.sources/location.xml';
  const LOCATION = '/sources/location.xml';
  const LOGIN_NEW = '/new.sources/login.xml';
  const LOGIN = '/sources/login.xml';
  const PARSEINIFILE_INI = '/sources/parse_ini_file.ini';
  const PARSEINIFILE_NEW = '/new.sources/parse_ini_file.xml';
  const PARSEINIFILE = '/sources/parse_ini_file.xml';
  const PATHWAY_NEW = '/new.sources/pathway.xml';
  const PATHWAY = '/sources/pathway.xml';
}
