<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::One
 */
class MapFilter_Test_Unit_TreePattern_One extends
    PHPUnit_Framework_TestCase
{
  
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
    
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <one flag="one" assert="one">
            <attr flag="f0" assert="a0">Attr0</attr>
            <attr flag="f1" assert="a1">Attr1</attr>
          </one>
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern );

    $given = $filter->setQuery ( $query )->fetchResult ();

    $this->assertSame ( $result, $given->getResults () );
    $this->assertSame ( $asserts, $given->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $given->getFlags ()->getAll () );
  }
}
