<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Some
 */
class MapFilter_Test_Unit_TreePattern_Some extends
    PHPUnit_Framework_TestCase
{
  
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
        <pattern>
          <some flag="some" assert="some">
            <attr flag="f0" assert="a0">Attr0</attr>
            <attr flag="f1" assert="a1">Attr1</attr>
          </some>
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern );

    $given = $filter->setQuery ( $query )->fetchResult ();

    $this->assertSame ( $result, $given->getResults () );
    $this->assertSame ( $asserts, $given->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $given->getFlags ()->getAll () );
  }
}
