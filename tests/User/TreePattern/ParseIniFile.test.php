<?php
/**
 * Parse .ini File
 */

require_once PHP_TREEPATTERN_CLASS;

/**
 * @group       User
 * @group       User::TreePattern
 * @group       User::TreePattern::ParseIniFile
 */
class MapFilter_Test_User_TreePattern_ParseIniFile extends
    PHPUnit_Framework_TestCase
{

  const EXPAND_SECTIONS = TRUE;
 
  /**@{*/
  public function testParse () {
  
    $content = parse_ini_file (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_INI,
        self::EXPAND_SECTIONS
    );

    $pattern = MapFilter_TreePattern::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_XML
    );
    
    $filter = new MapFilter ( $pattern, $content );
    
    $result = $filter->fetchResult ();

    $this->assertEquals (
        $content,
        $result->getResults ()
    );
  }
  /**@}*/
}
