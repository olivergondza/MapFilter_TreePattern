<?php
/**
* User Tests
*/  

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group       User
 * @group	User::TreePattern
 */
class MapFilter_Test_User_TreePattern extends PHPUnit_Framework_TestCase {

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
        Array ( '
            <node_attr attr="attr">
              <all />
              <all />
            </node_attr>',
            2,
            'node_attr'
        ),
    );
  }
  
  /**
   * @dataProvider      provideWrongCount
   */
  public function testWrongCount ( $patternStr, $count, $node ) {

    try {

      $pattern = MapFilter_TreePattern_Xml::load ( $patternStr );
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
  
  /**
   * @expectedException MapFilter_TreePattern_InvalidPatternAttributeException
   * @expectedExceptionMessage Node 'key_attr' has no attribute like 'wrongattr'.
   *
   * @covers MapFilter_TreePattern_InvalidPatternAttributeException
   * @covers MapFilter_TreePattern_Tree_Builder
   */
  public function testInvalidAttr () {
  
    $pattern = '
        <pattern>
          <key_attr attr="attrName" wrongattr="wrongAttrName">
            <attr forValue="thisName">thisAttr</attr>
          </key_attr>
        </pattern>
    ';
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  public function provideSimpleOneWhitelist () {
  
    return Array (
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => 'a.out' ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-o' => 'a.out' ),
            null
        ),
        Array (
            Array (),
            null
        )
    );
  }
  
  /**@{*/
  /**
   * @dataProvider      provideSimpleOneWhitelist
   *
   * @covers MapFilter_TreePattern_Tree_Node_One
   */
  public function testSimpleOneWhitelist ( $query, $result ) {
  
    $pattern = '
        <pattern>
          <one>
            <attr>-h</attr>
            <attr>-v</attr>
          </one>
        </pattern>
    ';
    
    // Instantiate MapFilter with pattern and query
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    // Get desired result
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  /**@}*/

  public function provideSimpleAllWhitelist () {
  
    return Array (
        Array (
            Array ( '-f' => NULL, '-o' => NULL, '-v' => NULL ),
            Array ( '-f' => NULL, '-o' => NULL )
        )
    );
  }

  /*@{*/
  /**
   * @dataProvider      provideSimpleAllWhitelist
   *
   * @covers MapFilter_TreePattern_Tree_Node_All
   */
  public function testSimpleAllWhitelist ( $query, $result ) {
  
    $pattern = '
        <pattern>
          <all>
            <attr>-f</attr>
            <attr>-o</attr>
          </all>
        </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  /*@}*/
  
  public function provideSimpleOptWhitelist () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( '-h' => NULL, '-v' => NULL ),
            Array ( '-h' => NULL, '-v' => NULL )
        ),
        Array (
            Array ( '-v' => NULL ),
            Array ( '-v' => NULL )
        ),
        Array (
            Array ( '-h' => NULL ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => 'a.out' ),
            Array ( '-h' => NULL, '-v' => NULL )
        )
    );
  }
  
  /*@{*/
  /**
   * @dataProvider      provideSimpleOptWhitelist
   *
   * @covers MapFilter_TreePattern_Tree_Node_Opt
   */
  public function testSimpleOptWhitelist ( $query, $result ) {
    
    $pattern = '
        <pattern>
          <opt>
            <attr>-h</attr>
            <attr>-v</attr>
          </opt>
        </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  /*@{*/
  
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

  public function provideTautology () {

    return Array (
        Array ( NULL ),
        Array ( TRUE ),
        Array ( 0 ),
        Array ( .1 ),
        Array ( 'a' ),
        Array ( Array ( 'a' ) ),
        Array ( new MapFilter ),
        Array ( xml_parser_create () ),
    );
  }

  /**
   * @dataProvider      provideTautology
   * 
   * @covers MapFilter_TreePattern_Tree_Value
   */
  public function testTautology ( $data ) {
  
    $tautology = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <value flag="valueFlag" assert="valueAssert" />
        </pattern>
    ' );
    
    $filter = new MapFilter ( $tautology, $data );
    
    $result = $filter->fetchResult ();
    
    $this->assertEquals ( $data, $result->getResults () );
    $this->assertEquals ( Array (), $result->getAsserts ()->getAll () );
    $this->assertEquals ( Array ( 'valueFlag' ), $result->getFlags ()->getAll () );
  }
}
