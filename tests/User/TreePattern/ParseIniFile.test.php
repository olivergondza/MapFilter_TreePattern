<?php
/**
 * Parse .ini File
 */

require_once 'PHP/MapFilter/TreePattern.php';

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
  public function testParseIniFile () {
  
    $content = parse_ini_file (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_INI,
        self::EXPAND_SECTIONS
    );

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE
    );
    
    $filter = new MapFilter ( $pattern, $content );
    
    $result = $filter->fetchResult ();

    $this->assertEquals (
        $content,
        $result->getResults ()
    );
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
    
    $filter = new MapFilter ( $pattern, $content );
    
    $result = $filter->fetchResult ();

    $this->assertEquals (
        $content,
        $result->getResults ()
    );
  }
  /**@}*/
}
