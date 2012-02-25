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
          <all flag="all" assert="all">
            <attr flag="f0" assert="a0">Attr0</attr>
            <attr flag="f1" assert="a1">Attr1</attr>
          </all>
        </pattern>
    '
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}