<?php
/**
* Trivial Tests
*/  

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_Trivial extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideSimpleOneWhitelist () {
  
    return Array (
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => 'a.out' ),
            Array ( '-h' => NULL ),
            true
        ),
        Array (
            Array ( '-o' => 'a.out' ),
            null,
            false
        ),
        Array (
            Array (),
            null,
            false
        )
    );
  }
  
  /**@{*/
  /**
   * @dataProvider      provideSimpleOneWhitelist
   *
   * @covers MapFilter_TreePattern_Tree_Node_One
   */
  public function testSimpleOneWhitelist ( $query, $result, $valid ) {
  
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
    
    $this->assertEquals (
        $valid,
        $filter->fetchResult ()->isValid ()
    );
  }
  /**@}*/

  public function provideSimpleAllWhitelist () {
  
    return Array (
        Array (
            Array ( '-f' => NULL, '-o' => NULL, '-v' => NULL ),
            Array ( '-f' => NULL, '-o' => NULL ),
            true
        )
    );
  }

  /*@{*/
  /**
   * @dataProvider      provideSimpleAllWhitelist
   *
   * @covers MapFilter_TreePattern_Tree_Node_All
   */
  public function testSimpleAllWhitelist ( $query, $expected, $valid ) {
  
    $pattern = '
        <pattern>
          <all>
            <attr>-f</attr>
            <attr>-o</attr>
          </all>
        </pattern>
    ';
    
    $actual = MapFilter_TreePattern_Xml::load ( $pattern )
        ->getFilter ( $query )
        ->fetchResult ()
    ;
    
    $this->assertEquals ( $expected, $actual->getResults () );
    $this->assertEquals ( $valid, $actual->isValid () );
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
  public function testSimpleOptWhitelist ( $query, $expected ) {
    
    $pattern = '
        <pattern>
          <opt>
            <attr>-h</attr>
            <attr>-v</attr>
          </opt>
        </pattern>
    ';
    
    $actual = MapFilter_TreePattern_Xml::load ( $pattern )
        ->getFilter ( $query )
        ->fetchResult ()
    ;
    
    $this->assertEquals ( $expected, $actual->getResults () );
    $this->assertTrue ( $actual->isValid () );
  }
  /*@{*/
}
