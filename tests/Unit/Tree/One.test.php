<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Node_One<extended>
 * @covers MapFilter_TreePattern_Tree_Node_One_Builder
 */
class MapFilter_Test_Unit_TreePattern_One extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideContradition () {

    return Array (
        Array ( null ),
        Array ( true ),
        Array ( 0 ),
        Array ( .1 ),
        Array ( 'a' ),
        Array ( Array ( 'a' ) ),
        Array ( new MapFilter ),
        Array ( xml_parser_create () ),
    );
  }

  /**
   * @dataProvider      provideContradition
   */
  public function testContradition ( $data ) {
  
    $tautology = MapFilter_TreePattern_Xml::load ( '
      <!-- TreePattern_Contradition__ -->
        <pattern>
          <one />
        </pattern>
      <!-- __TreePattern_Contradition -->
    ' );
    
    
    $this->assertResultsEquals ( $tautology, $data, null );
    
    $tautology = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <one flag="valueFlag" assert="valueAssert" />
        </pattern>
    ' );
    
    $asserts = Array ( 'valueAssert' );
    
    $this->assertResultsEquals ( $tautology, $data, null, $asserts );
  }
  
  public function provideSimpleOneNode () {
  
    return Array (
        Array (
            Array (),
            null,
            Array ( 'a0', 'a1', 'one' ),
            Array (),
        ),
        Array (
            Array ( 'Attr0' => 0 ),
            Array ( 'Attr0' => 0 ),
            Array (),
            Array ( 'f0', 'one' ),
        ),
        Array (
            Array ( 'Attr1' => 1 ),
            Array ( 'Attr1' => 1 ),
            Array ( 'a0' ),
            Array ( 'f1', 'one' ),
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0 ),
            Array (),
            Array ( 'f0', 'one' ),
        ),
        Array (
            Array ( 'Attr1' => 1, 'Attr0' => 0 ),
            Array ( 'Attr0' => 0 ),
            Array (),
            Array ( 'f0', 'one' ),
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0 ),
            Array (),
            Array ( 'f0', 'one' ),
        ),
        Array (
            Array ( 'Attr1' => 1, 'Attr0' => 0, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0 ),
            Array (),
            Array ( 'f0', 'one' ),
        ),
    );
  }
  
  /**
   * Test OneNode with simple values
   *
   * @dataProvider      provideSimpleOneNode
   */
  public function testSimpleOneNode ( $query, $result, $asserts, $flags ) {
    
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <!-- TreePattern_One__ -->
        <pattern>
          <one flag="one" assert="one">
            <key flag="f0" assert="a0" name="Attr0" />
            <key flag="f1" assert="a1" name="Attr1" />
          </one>
        </pattern>
        <!-- __TreePattern_One -->
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
