<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Node_Some<extended>
 * @covers MapFilter_TreePattern_Tree_Node_Some_Builder
 */
class MapFilter_Test_Unit_TreePattern_Some extends
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
          <some />
        </pattern>
      <!-- __TreePattern_Contradition -->
    ' );
    
    
    $this->assertResultsEquals ( $tautology, $data, null );
    
    $tautology = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <some flag="valueFlag" assert="valueAssert" />
        </pattern>
    ' );
    
    $asserts = Array ( 'valueAssert' );
    
    $this->assertResultsEquals ( $tautology, $data, null, $asserts );
  }
  
  public function provideSimpleSomeNode () {
  
    return Array (
        Array (
            Array (),
            null,
            Array ( 'a0', 'a1', 'some' ),
            Array ()
        ),
        Array (
            Array ( 'Attr0' => 0 ),
            Array ( 'Attr0' => 0 ),
            Array ( 'a1' ),
            Array ( 'f0', 'some' )
        ),
        Array (
            Array ( 'Attr1' => 1 ),
            Array ( 'Attr1' => 1 ),
            Array ( 'a0' ),
            Array ( 'f1', 'some' )
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array (),
            Array ( 'f0', 'f1', 'some' )
        ),
        Array (
            Array ( 'Attr2' => 2 ),
            null,
            Array ( 'a0', 'a1', 'some' ),
            Array ()
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array (),
            Array ( 'f0', 'f1', 'some' )
        ),
    );
  }
  
  /**
   * Test OptNode with simple values
   *
   * @dataProvider      provideSimpleSomeNode
   */
  public function testSimpleSomeNode ( $query, $result, $asserts, $flags ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <!-- TreePattern_Some__ -->
        <pattern>
          <some flag="some" assert="some">
            <key flag="f0" assert="a0" name="Attr0" />
            <key flag="f1" assert="a1" name="Attr1" />
          </some>
        </pattern>
        <!-- __TreePattern_Some -->
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
