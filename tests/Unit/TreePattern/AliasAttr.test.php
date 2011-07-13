<?php
/**
 * Require tested class
 */
require_once PHP_TREEPATTERN_CLASS;

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::AliasAttr
 */
class
    MapFilter_Test_Unit_TreePattern_AliasAttr
extends
    PHPUnit_Framework_TestCase
{  
  
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

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/"/>
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $filter->fetchResult ()->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $filter->fetchResult ()->getAsserts ()
    );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException
   * @expectedExceptionMessage  Only allowed follower for AliasAttribute is Attr.
   */
  public function testDisallowedFollowerException () {
  
    $pattern = '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <alias attr="other_num" />
          </alias>
        </pattern>
    ';
  
    $pattern = MapFilter_TreePattern::load ( $pattern );
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

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <attr>number</attr>
          </alias>
        </pattern>
    '
    );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $filter->fetchResult ()->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $filter->fetchResult ()->getAsserts ()
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

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <attr>number</attr>
            <attr valuePattern="/(?!)/" default="yes">value</attr>
          </alias>
        </pattern>
    '
    );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $filter->fetchResult ()->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $filter->fetchResult ()->getAsserts ()
    );
  }
}
