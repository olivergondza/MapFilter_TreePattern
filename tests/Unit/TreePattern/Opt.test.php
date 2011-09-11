<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Opt
 *
 * @covers MapFilter_TreePattern_Tree_Node_Opt<extended>
 * @covers MapFilter_TreePattern_Tree_Node_Opt_Builder
 */
class MapFilter_Test_Unit_TreePattern_Opt extends PHPUnit_Framework_TestCase {  
  
  public function provideSimpleOptNode () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array ( 'opt' ),
            Array ( 'a0', 'a1' ),
        ),
        Array (
            Array ( 'Attr0' => 0 ),
            Array ( 'Attr0' => 0 ),
            Array ( 'f0', 'opt' ),
            Array ( 'a1' ),
        ),
        Array (
            Array ( 'Attr1' => 1 ),
            Array ( 'Attr1' => 1 ),
            Array ( 'f1', 'opt' ),
            Array ( 'a0' ),
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'f0', 'f1', 'opt' ),
            Array (),
        ),
        Array (
            Array ( 'Attr2' => 2 ),
            Array (),
            Array ( 'opt' ),
            Array ( 'a0', 'a1' ),
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'f0', 'f1', 'opt' ),
            Array (),
        ),
    );
  }
  
  /**
   * Test OptNode with simple values
   *
   * @dataProvider      provideSimpleOptNode
   */
  public function testSimpleOptNode ( $query, $result, $flags, $asserts ) {
    
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <opt flag="opt" assert="opt">
            <attr flag="f0" assert="a0">Attr0</attr>
            <attr flag="f1" assert="a1">Attr1</attr>
          </opt>
        </pattern>
    ' );

    $given = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertSame ( $result, $given->getResults () );
    $this->assertSame ( $asserts, $given->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $given->getFlags ()->getAll () );
  }
}
