<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::AliasAttr
 *
 * @covers MapFilter_TreePattern_Tree_Leaf_AliasAttr<extended>
 * @covers MapFilter_TreePattern_Tree_Leaf_AliasAttr_Builder<extended>
 * @covers MapFilter_TreePattern_Tree_Attribute
 */
class MapFilter_Test_Unit_TreePattern_AliasAttr extends
    PHPUnit_Framework_TestCase
{
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException
   * @expectedExceptionMessage  Only allowed follower for AliasAttribute is Attr.
   *
   * @covers MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException
   */
  public function testDisallowedFollowerException () {
  
    $pattern = '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <alias attr="other_num" />
          </alias>
        </pattern>
    ';
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  public function provideEmptyAliasAttr () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'aName' )
        ),
        Array (
            Array ( 'num' => 'hey' ),
            Array (),
            Array (),
            Array ( 'aName' => Array (
                MapFilter_TreePattern_Asserts::VALUE => 'hey'
            ) )
        ),
        Array (
            Array ( 'num' => 0 ),
            Array (),
            Array ( 'fName' ),
            Array ()
        ),
        Array (
            Array ( 'num' => 0, 'name' => 'asdf' ),
            Array (),
            Array ( 'fName' ),
            Array ()
        ),
    );
  }
  
  /**
   * Test empty alias
   *
   * @dataProvider      provideEmptyAliasAttr
   */
  public function testEmptyAliasAttr (
      $query, $result, $flags, $asserts
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/"/>
        </pattern>
    ' );

    $actual = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $result, $actual->getResults () );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $actual->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $actual->getAsserts ()
    );
  }
  
  public function provideOneToOneAliasAttr () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'aName' => 'aName' )
        ),
        Array (
            Array ( 'num' => 'hey' ),
            Array (),
            Array (),
            Array ( 'aName' => Array (
                MapFilter_TreePattern_Asserts::VALUE => 'hey'
            ) )
        ),
        Array (
            Array ( 'num' => 0 ),
            Array ( 'number' => 0 ),
            Array ( 'fName' ),
            Array ()
        ),
        Array (
            Array ( 'num' => 0, 'name' => 'asdf' ),
            Array ( 'number' => 0 ),
            Array ( 'fName' ),
            Array ()
        ),

    );
  }
  
  /**
   * Test transalte num to number
   *
   * @dataProvider      provideOneToOneAliasAttr
   */
  public function testOneToOneAliasAttr (
      $query, $result, $flags, $asserts
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <attr>number</attr>
          </alias>
        </pattern>
    '
    );

    $actual = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $result, $actual->getResults () );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $actual->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $actual->getAsserts ()
    );
  }
  
  public function provideOneToManyAliasAttr () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'aName' => 'aName' )
        ),
        Array (
            Array ( 'num' => 'hey' ),
            Array (),
            Array (),
            Array ( 'aName' => Array (
                MapFilter_TreePattern_Asserts::VALUE => 'hey'
            ) )
        ),
        Array (
            Array ( 'num' => 0 ),
            Array ( 'number' => 0, 'value' => 'yes' ),
            Array ( 'fName' ),
            Array ()
        ),
        Array (
            Array ( 'num' => 0, 'name' => 'asdf' ),
            Array ( 'number' => 0, 'value' => 'yes' ),
            Array ( 'fName' ),
            Array ()
        ),

    );
  }
  
  /**
   * Test transalte num to number
   *
   * @dataProvider      provideOneToManyAliasAttr
   */
  public function testOneToManyAliasAttr (
      $query, $result, $flags, $asserts
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <attr>number</attr>
            <attr valuePattern="/(?!)/" default="yes">value</attr>
          </alias>
        </pattern>
    '
    );

    $actual = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $result, $actual->getResults () );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $actual->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $actual->getAsserts ()
    );
  }
}
