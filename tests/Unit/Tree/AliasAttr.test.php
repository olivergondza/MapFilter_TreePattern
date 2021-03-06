<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Leaf_AliasAttr<extended>
 * @covers MapFilter_TreePattern_Tree_Leaf_AliasAttr_Builder<extended>
 * @covers MapFilter_TreePattern_Tree_Attribute
 */
class MapFilter_Test_Unit_TreePattern_AliasAttr extends
    MapFilter_TreePattern_Test_Functional
{
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException
   * @expectedExceptionMessage  Only allowed follower for Alias element is Key.
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
            Array ( 'aName' => 'hey' )
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
          <alias name="num" flag="fName" assert="aName" valuePattern="/\d/"/>
        </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideOneToOneAliasAttr () {
  
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
            Array ( 'aName' => 'hey' )
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
            <key name="number" />
          </alias>
        </pattern>
    '
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideOneToManyAliasAttr () {
  
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
            Array ( 'aName' => 'hey' )
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
            <key name="number" />
            <key name="value">
              <value pattern="/(?!)/" default="yes" />
            </key>
          </alias>
        </pattern>
    '
    );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
