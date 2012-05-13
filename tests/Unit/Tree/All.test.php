<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Node_All<extended>
 * @covers MapFilter_TreePattern_Tree_Node_All_Builder
 */
class MapFilter_Test_Unit_TreePattern_All extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideTautology () {

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
   * @dataProvider      provideTautology
   */
  public function testTautology ( $data ) {
  
    $tautology = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <all />
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $tautology, $data, $data );
    
    $tautology = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <all flag="valueFlag" assert="valueAssert" />
        </pattern>
    ' );
    
    $flags = Array ( 'valueFlag' );
    
    $this->assertResultsEquals ( $tautology, $data, $data, Array (), $flags );
  }
  
  public function provideSimpleAllNode () {
  
    return Array (
        Array (
            Array (),
            null,
            Array ( 'a0', 'all' ),
            Array (),
        ),
        Array (
            Array ( 'Attr0' => 0 ),
            null,
            Array ( 'a1', 'all' ),
            Array (),
        ),
        Array (
            Array ( 'Attr1' => 1 ),
            null,
            Array ( 'a0', 'all' ),
            Array (),
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array (),
            Array ( 'all', 'f0', 'f1' ),
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array (),
            Array ( 'all', 'f0', 'f1' ),
        )
    );
  }
  
  /**
   * Test AllNode with simple values
   *
   * @dataProvider      provideSimpleAllNode
   */
  public function testSimpleAllNode ( $query, $result, $asserts, $flags ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <!-- TreePattern_All__ -->
          <all flag="all" assert="all">
            <key flag="f0" assert="a0" name="Attr0" />
            <key flag="f1" assert="a1" name="Attr1" />
          </all>
          <!-- __TreePattern_All -->
        </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
