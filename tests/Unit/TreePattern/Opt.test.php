<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Opt
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
    
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <opt flag="opt" assert="opt">
            <attr flag="f0" assert="a0">Attr0</attr>
            <attr flag="f1" assert="a1">Attr1</attr>
          </opt>
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern, $query );

    $given = $filter->setQuery ( $query )->fetchResult ();

    $this->assertSame ( $result, $given->getResults () );
    $this->assertSame ( $asserts, $given->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $given->getFlags ()->getAll () );
  }
}
