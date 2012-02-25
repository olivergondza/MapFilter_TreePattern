<?php
/**
* User Tests
*/  

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern extends
    MapFilter_TreePattern_Test_Functional
{

  /**@{*/
  public function provideWrongCount () {
  
    return Array (
        Array (
            '<patterns><pattern></pattern></patterns>',
            0,
            'pattern'
        ),
        Array ( '
            <pattern>
              <all />
              <all />
            </pattern>',
            2,
            'pattern'
        ),
        Array ( '
            <patterns>
              <pattern>
                <opt></opt>
                <all></all>
              </pattern>
            </patterns>',
            2,
            'pattern'
        ),
    );
  }
  
  /**
   * @dataProvider      provideWrongCount
   */
  public function testWrongCount ( $patternStr, $count, $node ) {

    try {

      MapFilter_TreePattern_Xml::load ( $patternStr );
    } catch ( MapFilter_TreePattern_NotExactlyOneFollowerException $ex ) {

      $this->assertEquals (
          "The '$node' node must have exactly one follower but $count given.",
          $ex->getMessage ()
      );
    }
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage  The 'pattern' node must have exactly one follower but 2 given.
   */
  public function testMultipleTree () {
  
    MapFilter_TreePattern_Xml::load (
        '<pattern><opt></opt><all></all></pattern>'
    );
  }
  
  public function provideCompareStringAndFileLoad () {
  
    return Array (
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::LOCATION ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::LOGIN ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::COFFEE_MAKER ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::CAT ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::ACTION ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::FILTER ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DURATION ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::GENERATOR ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DIRECTION ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PATHWAY ),
        Array ( PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE ),
    );
  }

  /**
   * @dataProvider      provideCompareStringAndFileLoad
   *
   * @covers MapFilter_TreePattern_Xml::_loadXml
   */
  public function testCompareStringAndFileLoad ( $url ) {

    $fromFile = MapFilter_TreePattern_Xml::fromFile ( $url );
    $fromString = MapFilter_TreePattern_Xml::load (
        file_get_contents ( $url )
    );

    $this->assertEquals ( $fromFile, $fromString );
  }
}
