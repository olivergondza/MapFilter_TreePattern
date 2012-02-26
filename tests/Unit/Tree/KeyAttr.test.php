<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Leaf_KeyAttr<extended>
 * @covers MapFilter_TreePattern_Tree_Leaf_KeyAttr_Builder<extended>
 * @covers MapFilter_TreePattern_Tree_Attribute
 */
class MapFilter_Test_Unit_TreePattern_KeyAttr extends
    MapFilter_TreePattern_Test_Functional
{
  
  /** Obtain an attribute from KeyAttr node */
  public function testKeyAttrAttribute () {
  
    $attr = 'An attribute';
    
    $builder = new MapFilter_TreePattern_Tree_Leaf_KeyAttr_Builder ( 'keyattr' );
    $builder->setAttr ( $attr );
    
    $node = new MapFilter_TreePattern_Tree_Leaf_KeyAttr ($builder);
    
    $this->assertEquals (
        $attr,
        $node->getAttribute ()
    );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_InvalidPatternAttributeException
   * @expectedExceptionMessage Node 'key_attr' has no attribute like 'wrongattr'.
   *
   * @covers MapFilter_TreePattern_InvalidPatternAttributeException
   * @covers MapFilter_TreePattern_Tree_Builder
   */
  public function testInvalidAttr () {
  
    $pattern = '
        <pattern>
          <key_attr attr="attrName" wrongattr="wrongAttrName">
            <attr forValue="thisName">thisAttr</attr>
          </key_attr>
        </pattern>
    ';
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  public function provideKeyAttrCreate () {
  
    return Array (
        Array (
            Array ( 'no' => 'action' ),
            null,
        ),
        Array (
            Array ( 'action' => 'sickAction', 'task' => 'sickTask'),
            null,
        ),
        Array (
            Array ( 'action' => 'do', 'nothing' => 'All Day' ),
            null,
        ),
        Array (
            Array ( 'action' => 'do', 'task' => 'myTask' ),
            Array ( 'action' => 'do', 'task' => 'myTask' ),
        ),
        Array (
            Array ( 'action' => 'schedule', 'tasks' => 'All My Tasks' ),
            Array ( 'action' => 'schedule', 'tasks' => 'All My Tasks' ),
        ),
        Array (
            Array ( 'action' => 'do', 'task' => 'myTask', 'tasks' => 'My Tasks' ),
            Array ( 'action' => 'do', 'task' => 'myTask' ),
        ),
    );
  }
  
  /**
  * Test Create KeyAttr Node
  *
  * action => do ; task => ...
  * action => schedule; tasks => ...
  *
  * @dataProvider provideKeyAttrCreate
  */
  public function testKeyAttrCreate ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::load (  '
        <pattern>
          <key_attr attr="action">
            <attr forValue="do">task</attr>
            <attr forValue="schedule">tasks</attr>
          </key_attr>
        </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
  
  public function provideKeyAttrArrayValue () {
  
    return Array (
        Array (
            Array (),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' ),
            Array ()
        ),
        Array (
            Array ( 'order' => Array () ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'order' => new ArrayIterator ( Array () ) ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' => new ArrayIterator ( Array () ) ),
            Array (),
        ),
        Array (
            Array ( 'order' => new EmptyIterator () ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' => new EmptyIterator () ),
            Array (),
        ),
        Array (
            Array ( 'order' => Array ( 'first' ) ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator ( Array ( 'first' ) ) ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => Array ( 'first', 'first' ) ),
            Array ( 'order' => Array ( 'first', 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator (
                Array ( 'first', 'first' ) )
            ),
            Array ( 'order' => Array ( 'first', 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => Array ( 'first' ), 'attr0' => -1 ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => -1 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator (
                Array ( 'first' )
            ), 'attr0' => -1 ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => -1 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => Array ( 'first', 'second' ), 'attrn' => 'n' ),
            Array ( 'order' => Array ( 'first', 'second' ), 'attr0' => '0', 'attr1' => '1' ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator (
                Array ( 'first', 'second' )
            ), 'attrn' => 'n' ),
            Array ( 'order' => Array ( 'first', 'second' ), 'attr0' => '0', 'attr1' => '1' ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => new ArrayIterator ( Array ( 'attr0' ) ) ),
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'attr0', 'attr1' ) ),
            Array ( 'auto' => Array ( 'attr0', 'attr1' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => new ArrayIterator (
                Array ( 'attr0', 'attr1' )
            ) ),
            Array ( 'auto' => Array ( 'attr0', 'attr1' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'value' ) ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'value' ) ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'auto' => new ArrayIterator ( Array ( 'value' ) ) ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' ),
            Array (),
        ),
    );
  }
  
  /**
   * Test array filtering
   *
   * @dataProvider      provideKeyAttrArrayValue
   */
  public function testKeyAttrArrayValue (
      $query, $results, $asserts, $flags
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load (  '
        <pattern>
          <one>
            <key_attr
                attr="order"
                iterator="yes"
                assert="wrong_keyattr"
                flag="a_keyattr"
            >
              <attr forValue="first"  default="0">attr0</attr>
              <attr forValue="second" default="1">attr1</attr>
              <attr forValue="(?!first|second).*"  default="n">attrn</attr>
            </key_attr>
            <attr
                iterator="yes"
                valuePattern="attr."
                default="defaultValue"
            >auto</attr>
          </one>
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $results, $asserts, $flags );
  }

  public function provideKeyAttrDefaultValuePattern () {
  
    return Array (
        Array (
            Array (),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'INVALID_VALUE' ) ),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'INVALID_VALUE' ) ),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value' ) ),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value' ) ),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value1' ) ),
            Array ( 'keyattr' => Array ( 'value1' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value1' ) ),
            Array ( 'keyattr' => Array ( 'value1' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value0' ) ),
            Array ( 'keyattr' => Array ( 'value0' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value0' ) ),
            Array ( 'keyattr' => Array ( 'value0' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11' ) ),
            Array ( 'keyattr' => Array ( 'value11' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11' ) ),
            Array ( 'keyattr' => Array ( 'value11' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value10' ) ),
            Array ( 'keyattr' => Array ( 'value10' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value10' ) ),
            Array ( 'keyattr' => Array ( 'value10' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value10', 'value42' ) ),
            Array ( 'keyattr' => Array ( 'value10', 'value42' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11', 'value41' ) ),
            Array ( 'keyattr' => Array ( 'value11', 'value41' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11', 'value42' ) ),
            Array ( 'keyattr' => Array ( 'value11', 'value42' ), 'odd' => 'yes', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value12', 'value41' ) ),
            Array ( 'keyattr' => Array ( 'value12', 'value41' ), 'odd' => 'yes', 'even' => 'yes' )
        ),
    );
  }

  /**
   * @dataProvider      provideKeyAttrDefaultValuePattern
   */
  public function testKeyAttrDefaultValuePattern ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key_attr
              iterator="yes"
              attr="keyattr"
              default="value"
              valuePattern="value[0-9]*"
          >
            <attr forValue="value([0-9]*[13579])?" default="yes">even</attr>
            <attr forValue="value([0-9]*[02468])" default="yes">odd</attr>
          </key_attr>
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result );
  }

  public function provideValidationAndAssertationDefault () {
  
    return Array (
        Array (
            Array (),
            Array ( 'number' => '0', 'even' => '1' )
        ),
        Array (
            Array ( 'number' => '3', 'odd' => '1' ),
            Array ( 'number' => '1', 'odd' => '1' )
        ),
        Array (
            Array ( 'number' => '0', 'even' => TRUE ),
            Array ( 'number' => '0', 'even' => TRUE )
        ),
        Array (
            Array ( 'number' => '1', 'odd' => TRUE ),
            Array ( 'number' => '1', 'odd' => TRUE )
        ),
        Array (
            Array ( 'number' => '0' ),
            Array ( 'number' => '0', 'even' => '1' )
        ),
        Array (
            Array ( 'number' => '1' ),
            Array ( 'number' => '1', 'odd' => '1' )
        )
    );
  }

  /**
   * @dataProvider      provideValidationAndAssertationDefault
   */
  public function testValidationAndAssertationDefault (
      $query, $result
  ) {
 
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key_attr
              attr="number"
              validationDefault="1"
              existenceDefault="0"
              valuePattern="[01]+"
          >
            <attr forValue="[01]*0" existenceDefault="1">even</attr>
            <attr forValue="[01]*1" existenceDefault="1">odd</attr>
          </key_attr>
        </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
}
