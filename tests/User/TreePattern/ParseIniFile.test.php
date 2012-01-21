<?php
/**
 * Parse .ini File
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * Test .ini file parsing
 *
 * Better not use assertResultsEquals since methods are used as examples.
 */
class MapFilter_Test_User_TreePattern_ParseIniFile extends
    MapFilter_TreePattern_Test_Functional
{

  const EXPAND_SECTIONS = TRUE;
 
  /**@{*/
  public function testParseIniFile () {
  
    $content = parse_ini_file (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_INI,
        self::EXPAND_SECTIONS
    );

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE
    );
    
    $actual = $pattern->getFilter ( $content )->fetchResult ()->getResults ();

    $this->assertEquals ( $content, $actual );
  }
  /**@}*/
  
  /**@{*/
  public function testNewParseIniFile () {
  
    $content = parse_ini_file (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_INI,
        self::EXPAND_SECTIONS
    );

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_NEW
    );
    
    $actual = $pattern->getFilter ( $content )->fetchResult ()->getResults ();

    $this->assertEquals ( $content, $actual );
  }
  /**@}*/
}
