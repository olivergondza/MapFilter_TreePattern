<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Node_NodeAttr<extended>
 * @covers MapFilter_TreePattern_Tree_Node_NodeAttr_Builder<extended>
 */
class MapFilter_Test_Unit_TreePattern_NodeAttr extends
    MapFilter_TreePattern_Test_Functional
{
  
  /**
   * More than one node_attr follower
   *
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage  The 'node_attr' node must have exactly one follower but 2 given.
   */
  public function testMultipleFollower () {
  
    $pattern = '
        <pattern>
          <node_attr attr="attr">
            <attr>a</attr>
            <attr>b</attr>
          </node_attr>
        </pattern>
    ';

    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  /**
   * No node_attr follower
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage  The 'node_attr' node must have exactly one follower but 0 given.
   */
  public function testNoFollower () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <node_attr attr="attr">
          </node_attr>
        </pattern>
    ' );
  
    $query = Array ( 'attr' => Array ( 'attr' ) );
  
    $this->assertResultsEquals ( $pattern, $query, $query );
  }
  
  public function provideAssertAndFlags () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'attr0' => Array ( 'attr1' => 'val1' ) ),
            Array ( 'attr0' => Array ( 'attr1' => 'val1' ) ),
            Array ( 'attr1' ),
            Array ()
        ),
        Array (
            Array ( 'attr0' => Array ( 'attr0' => 'val0' ) ),
            Array (),
            Array (),
            Array ( 'attr1' )
        ),
        Array (
            Array ( 'attr0' => Array ( 'attr1' => 'val1', 'attr0' => 'val0' ) ),
            Array ( 'attr0' => Array ( 'attr1' => 'val1' ) ),
            Array ( 'attr1' ),
            Array ()
        ),
        Array (
            Array ( 'attr0' => Array () ),
            Array (),
            Array (),
            Array ()
        )
    );
  }
  
  /**
   * @dataProvider      provideAssertAndFlags
   */
  public function testAssertAndFlags ( $query, $result, $flags, $asserts ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
          <pattern>
            <node_attr attr="attr0">
              <attr flag="attr1" assert="attr1">attr1</attr>
            </node_attr>
          </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideMultipleCompare () {
  
    return Array (
        Array (
            Array (),
            null
        ),
        Array (
            Array ( 'value' => 1 ),
            null
        ),
        Array (
            Array ( 'value' => 1, 'one' => 'one' ),
            null
        ),
        Array (
            Array ( 'value' => 1, 'zero' => 'zero' ),
            null
        ),
        Array (
            Array ( 'value' => 1, 'one' => 'one', 'left' => 'hand' ),
            Array ( 'value' => 1, 'one' => 'one', 'left' => 'hand' )
        ),
        Array (
            Array ( 'value' => 0, 'zero' => 'zero', 'left' => 'hand' ),
            Array ( 'value' => 0, 'zero' => 'zero', 'left' => 'hand' )
        ),

        Array (
            Array ( 'value' => 1, 'one' => 'one', 'right' => 'hand' ),
            Array ( 'value' => 1, 'one' => 'one', 'right' => 'hand' )
        ),
        Array (
            Array ( 'value' => 0, 'zero' => 'zero', 'right' => 'hand' ),
            Array ( 'value' => 0, 'zero' => 'zero', 'right' => 'hand' )
        ),
        Array (
            Array ( 'value' => 2, 'one' => 'one', 'left' => 'hand' ),
            null
        ),
        Array (
            Array ( 'value' => 1, 'one' => 'one', 'up' => 'up', 'down' => 'down' ),
            Array ( 'value' => 1, 'one' => 'one', 'up' => 'up', 'down' => 'down' )
        ),
        Array (
            Array ( 'value' => 0, 'zero' => 'zero', 'up' => 'up', 'down' => 'down' ),
            Array ( 'value' => 0, 'zero' => 'zero', 'up' => 'up', 'down' => 'down' )
        ),
    );
  }
  
  /**
   * @dataProvider      provideMultipleCompare
   */
  public function testMultipleCompare ( $query, $result ) {

    $simple = MapFilter_TreePattern_Xml::load ( '
        <pattern>
            <all>
              <key_attr attr="value">
                <attr forValue="1">one</attr>
                <attr forValue="0">zero</attr>
              </key_attr>
              <one>
                <attr>left</attr>
                <attr>right</attr>
                <all>
                  <attr>up</attr>
                  <attr>down</attr>
                </all>
              </one>
            </all>
        </pattern>
    ' );
    
    $assembled = MapFilter_TreePattern_Xml::load ( '
        <patterns>
          <pattern>
            <all>
              <all attachPattern="KeyAttrPattern"/>
              <all attachPattern="OnePattern"/>
            </all>
          </pattern>

          <pattern name="KeyAttrPattern">
            <key_attr attr="value">
              <attr forValue="1">one</attr>
              <attr forValue="0">zero</attr>
            </key_attr>
          </pattern>

          <pattern name="OnePattern">
            <one>
              <attr>left</attr>
              <attr>right</attr>
              <all attachPattern="AllPattern"/>
            </one>
          </pattern>
          
          <pattern name="AllPattern">
            <all>
              <attr>up</attr>
              <attr>down</attr>
            </all>
          </pattern>
        </patterns>
    ' );

    $this->assertResultsEquals ( $simple, $query, $result );
    $this->assertResultsEquals ( $assembled, $query, $result );
  }
  
  public function provideCyclicParse () {
  
    return Array (
        Array (
            Array (),
            null
        ),
        Array (
            Array ( 'left' => Array ( 'value' => 1 ) ),
            null
        ),
        Array (
            Array ( 'left' => 1 ),
            null
        ),
        Array (
            Array ( 'value' => 42 ),
            Array ( 'value' => 42 )
        ),
        Array (
            Array (
                'left' => Array (
                    'value' => 1
                ),
                'right' => Array (
                    'value' => 0
                )
            ),
            Array (
                'left' => Array (
                    'value' => 1
                ),
                'right' => Array (
                    'value' => 0
                )
            )
        ),
        Array (
            Array (
                'left' => Array (
                    'left' => Array ( 'value' => 0 ),
                    'right' => Array ( 'value' => 1 )
                ),
                'right' => Array (
                    'value' => 2
                )
            ),
            Array (
                'left' => Array (
                    'left' => Array ( 'value' => 0 ),
                    'right' => Array ( 'value' => 1 )
                ),
                'right' => Array (
                    'value' => 2
                )
            )
        )
    );
  }
  
  /**
   * @dataProvider      provideCyclicParse
   */
  public function testCyclicParse ( $query, $result ) {

    $assembled = MapFilter_TreePattern_Xml::load ( '
        <patterns>
          <pattern>
            <one>
              <all>
                <node_attr attr="left" attachPattern="main"/>
                <node_attr attr="right" attachPattern="main"/>
              </all>
              <attr>value</attr>
            </one>
          </pattern>
        </patterns>
    ' );

    $this->assertResultsEquals ( $assembled, $query, $result );
  }
  
  public function provideIteratorParse () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'links' )
        ),
        Array (
            Array ( 'url' => 'my.url.com' ),
            Array (),
            Array (),
            Array ( 'links' )
        ),
        Array (
            Array ( 'links' => 'my.url.com' ),
            Array (),
            Array (),
            Array ( 'links' )
        ),
        Array (
            Array ( 'links' => Array ( 'link' => 'my.url.com' ) ),
            Array (),
            Array (),
            Array ( 'links' )
        ),
        Array (
            Array ( 'links' => Array ( 'url' => 'my.url.com', 'title' => 'A' ) ),
            Array (),
            Array (),
            Array ( 'links' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'title', 'url' ),
            Array ()
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'my.url.com' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'title', 'url' ),
            Array ( 'title' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'url' => 'my.url.com' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'title', 'url' ),
            Array ( 'title' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'title' => 'A' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'title', 'url' ),
            Array ( 'title' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'url' => 'my.url.com', 'title' => 'B' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'url' => 'my.url.com', 'title' => 'B' )
                )
            ),
            Array ( 'links', 'title', 'url' ),
            Array ()
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    'url' => 'my.url.com'
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'title', 'url' ),
            Array ()
        ),
        Array (
            Array (
                'links' => Array ( Array () )
            ),
            Array (),
            Array (),
            Array ( 'links', 'title' ),
        ),
        Array (
            Array (
                'links' => Array ()
            ),
            Array (),
            Array (),
            Array ( 'links' ),
        ),
    );
  }
  
  /**
   * @dataProvider      provideIteratorParse
   */
  public function testIteratorParse (
      $query, $result, $flags, $asserts
  ) {
  
    $assembled = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <node_attr attr="links" iterator="yes" flag="links" assert="links">
            <all>
              <attr flag="url" assert="title">url</attr>
              <attr flag="title" assert="title">title</attr>
            </all>
          </node_attr>
        </pattern>
    ' );

    $this->assertResultsEquals ( $assembled, $query, $result, $asserts, $flags );
  }
}
