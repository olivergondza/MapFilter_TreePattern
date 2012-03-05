<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Iterator
 * @covers MapFilter_TreePattern_Tree_Iterator_Builder
 */
class MapFilter_Test_Unit_TreePattern_Iterator extends
    MapFilter_TreePattern_Test_Functional
{

  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'iterator' has no content.
   *
   * @covers MapFilter_TreePattern_Tree_InvalidContentException
   * @covers MapFilter_TreePattern_Tree_Iterator_Builder<extended>
   */
  public function testTextContent () {
  
    MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator>value</iterator>
        </pattern>
    ' );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'iterator' node must have exactly one follower but 2 given.
   *
   * @covers MapFilter_TreePattern_NotExactlyOneFollowerException
   * @covers MapFilter_TreePattern_Tree_Iterator_Builder
   */
  public function testNotExactlyOneFollower () {
  
    MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator>
            <all />
            <opt />
          </iterator>
        </pattern>
    ' );
  }
  
  public function provideNonIntegralLengthConstraint () {
  
    return Array (
        Array ( '<iterator min="-1" />' ),
        Array ( '<iterator min="ten" />' ),
        Array ( '<iterator max="-1" />' ),
        Array ( '<iterator max="ten" />' ),
        Array ( '<iterator max="1" min="-1" />' ),
        Array ( '<iterator min="1" max="-1" />' ),
        Array ( '<iterator max="-1" min="-1" />' ),
    );
  }
  
  /**
   * @dataProvider provideNonIntegralLengthConstraint
   *
   * @expectedException MapFilter_TreePattern_Tree_Iterator_InvalidLengthConstraintException
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   * @covers MapFilter_TreePattern_Tree_Iterator_InvalidLengthConstraintException
   */
  public function testInvalidLengthConstraint ( $pattern ) {
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_Iterator_EmptyIntervalSpecifiedException
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   * @covers MapFilter_TreePattern_Tree_Iterator_EmptyIntervalSpecifiedException
   */
  public function testEmptyIntervalSpecified () {
  
    MapFilter_TreePattern_Xml::load ( '
        <iterator min="2" max="1" />
    ' );
  }
  
  public function provideEmptyIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'no_iterator' ),
            Array ()
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'iterator' )
        ),
        Array (
            Array ( 'some', 'content' ),
            Array ( 'some', 'content' ),
            Array (),
            Array ( 'iterator' )
        ),
        Array (
            4,
            null,
            Array ( 'no_iterator' ),
            Array ()
        ),
        Array (
            'string',
            null,
            Array ( 'no_iterator' ),
            Array ()
        ),
        Array (
            Array ( 'key' => 'value' ),
            Array ( 'key' => 'value' ),
            Array (),
            Array ( 'iterator' )
        ),
    );
  }
  
  /**
   * @dataProvider provideEmptyIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testEmptyIterator ( $query, $result, $asserts, $flags ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="iterator" assert="no_iterator" />
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideStructIterator () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'iterator' )
        ),
        Array (
            Array ( Array ( 'int' => 42 ) ),
            Array ( Array ( 'int' => 42 ) ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array ( 42 ),
            Array ( 42 ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array ( Array ( 'int' => 42 ), Array ( 'int' => 43 ), Array ( 'int' => 44 ) ),
            Array ( Array ( 'int' => 42 ), Array ( 'int' => 43 ), Array ( 'int' => 44 ) ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array ( 42, 43, 44 ),
            Array ( 42, 43, 44 ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array ( Array ( 'int' => 42 ), 43 ),
            Array ( Array ( 'int' => 42 ), 43 ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array ( Array ( 'int' => 'a number' ) ),
            Array ( Array ( 'int' => 'zero' ) ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array (
                Array ( 'int' => 42 ), Array ( 'int' => 'a number' ),
                Array ( 'int' => 43 ), Array ( 'int' => 'string' )
            ),
            Array (
                Array ( 'int' => 42 ), Array ( 'int' => 'zero' ),
                Array ( 'int' => 43 ), Array ( 'int' => 'zero' )
            ),
            Array (),
            Array ( 'int', 'iterator' )
        ),
        Array (
            Array ( 'val' ),
            Array (),
            Array ( 'no_int' ),
            Array ( 'iterator' )
        ),
        Array (
            Array ( 'string' => 'val' ),
            Array (),
            Array ( 'no_int' ),
            Array ( 'iterator' )
        ),
        Array (
            'val',
            null,
            Array ( 'no_iterator' ),
            Array ()
        ),
    );
  }
  
  /**
   * @dataProvider provideStructIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testStructIterator ( $query, $result, $asserts, $flags ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="iterator" assert="no_iterator">
            <one flag="int" assert="no_int">
              <value pattern="/^\d+$/" />
              <key name="int">
                <value pattern="/^\d+$/" default="zero" />
              </key>
            </one>
          </iterator>
        </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideKeyIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'no_iterator' ),
            Array (),
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( 'key' => 7 ),
            Array (),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( 7 ),
            Array (),
            Array (  'no_key1', 'no_key2'  ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( Array ( 'key1' => 7 ) ),
            Array ( Array ( 'key1' => 7 ) ),
            Array (),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key2' => 7 ) ),
            Array ( Array ( 'key2' => 7 ) ),
            Array ( 'no_key1' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 7 ), Array ( 'key1' => 8 ) ),
            Array ( Array ( 'key1' => 7 ), Array ( 'key1' => 8 ) ),
            Array (),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key2' => 7 ), Array ( 'key2' => 8 ) ),
            Array ( Array ( 'key2' => 7 ), Array ( 'key2' => 8 ) ),
            Array ( 'no_key1' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 7 ), Array ( 'key2' => 8 ) ),
            Array ( Array ( 'key1' => 7 ), Array ( 'key2' => 8 ) ),
            Array ( 'no_key1' ),
            Array ( 'iterator', 'key1', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 7 ), Array ( 'key2' => 8 ) ),
            Array ( Array ( 'key1' => 7 ), Array ( 'key2' => 8 ) ),
            Array ( 'no_key1' ),
            Array ( 'iterator', 'key1', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid' ), Array ( 'key2' => 8 ) ),
            Array ( 1 => Array ( 'key2' => 8 ) ),
            Array ( 'no_key1' => 'invalid', 'no_key2' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 8 ), Array ( 'key2' => 'invalid' ) ),
            Array ( Array ( 'key1' => 8 ) ),
            Array ( 'no_key1', 'no_key2' => 'invalid' ),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid' ), Array ( 'key1' => 8 ) ),
            Array ( 1 => Array ( 'key1' => 8 ) ),
            Array ( 'no_key1' => 'invalid', 'no_key2' ),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key1' => 8 ), Array ( 'key1' => 'invalid' ) ),
            Array ( Array ( 'key1' => 8 ) ),
            Array ( 'no_key1' => 'invalid', 'no_key2' ),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key2' => 'invalid' ), Array ( 'key2' => 8 ) ),
            Array ( 1 => Array ( 'key2' => 8 ) ),
            Array ( 'no_key1', 'no_key2' => 'invalid' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key2' => 8 ), Array ( 'key2' => 'invalid' ) ),
            Array ( Array ( 'key2' => 8 ) ),
            Array ( 'no_key1', 'no_key2' => 'invalid' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array (
                Array ( 'wrong' => 1 ), Array ( 'key1' => 7 ),
                Array ( 'wrong' => 2 ), Array ( 'key2' => 8 ),
                Array ( 'wrong' => 3 )
            ),
            Array ( 1 => Array ( 'key1' => 7 ), 3 => Array ( 'key2' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key1', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 6, 'key2' => 7 ) ),
            Array ( Array ( 'key1' => 6 ) ),
            Array (),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid' ) ),
            Array (),
            Array ( 'no_key1' => 'invalid', 'no_key2' ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( Array ( 'key2' => 'invalid' ) ),
            Array (),
            Array ( 'no_key1', 'no_key2' => 'invalid' ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid', 'key2' => 'invalid' ) ),
            Array (),
            Array ( 'no_key1' => 'invalid', 'no_key2' => 'invalid' ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid', 'key2' => 7 ) ),
            Array ( Array ( 'key2' => 7 ) ),
            Array ( 'no_key1' => 'invalid' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 7, 'key2' => 'invalid' ) ),
            Array ( Array ( 'key1' => 7 ) ),
            Array (),
            Array ( 'iterator', 'key1' ),
        ),
    );
  }
  
  /**
   * @dataProvider provideKeyIterator
   */
  public function testKeyIterator ( $query, $result, $asserts, $flags ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="iterator" assert="no_iterator">
            <one>
              <key name="key1" flag="key1" assert="no_key1">
                <value pattern="/^\d+$/" />
              </key>
              <key name="key2" flag="key2" assert="no_key2">
                <value pattern="/^\d+$/" />
              </key>
            </one>
          </iterator>
        </pattern>
    ' );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideConstrainedIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array ( 1 ),
            Array ( 1 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 2 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 2, 3 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array (),
            null,
            Array ( 'it' ),
            Array ()
        ),
    );
  }
  
  /**
   * @dataProvider provideConstrainedIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testConstrainedIterator (
      $query, $result, $asserts, $flags
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="it" assert="it" min="1" max="2">
            <value flag="val" assert="flag" />
          </iterator>
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideTopConstrainedIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'it' )
        ),
        Array (
            Array ( 1 ),
            Array ( 1 ),
            Array (),
            Array ( 'it' )
        ),
        Array (
            Array ( 1, 2 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it' )
        ),
        Array (
            Array ( 1, 2, 3 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it' )
        ),
    );
  }
  
  /**
   * @dataProvider provideTopConstrainedIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testTopConstrainedIterator (
      $query, $result, $asserts, $flags
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="it" assert="it" max="2" />
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideBottomConstrainedIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array (),
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array ( 1 ),
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array ( 1, 2 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it' )
        ),
        Array (
            Array ( 1, 2, 3 ),
            Array ( 1, 2, 3 ),
            Array (),
            Array ( 'it' )
        ),
    );
  }
  
  /**
   * @dataProvider provideBottomConstrainedIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testBottomConstrainedIterator (
      $query, $result, $asserts, $flags
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="it" assert="it" min="2" />
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideValidatingConstrainedIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array (),
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array ( 1 ),
            Array ( 1 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 2 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 2, 3 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 'hello', 'world' ),
            null,
            Array ( 'flag' => 'world', 'it' ),
            Array ()
        ),
        Array (
            Array ( 'hello', 1, 'world' ),
            Array ( 1 => 1 ),
            Array ( 'flag' => 'world' ),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 'hello', 1, 'world', 2 ),
            Array ( 1 => 1, 3 => 2 ),
            Array ( 'flag' => 'world' ),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 'hello', 2, 'world', 3 ),
            Array ( 0 => 1, 2 => 2 ),
            Array ( 'flag' => 'hello' ),
            Array ( 'it', 'val' )
        ),
    );
  }
  
  /**
   * @dataProvider provideValidatingConstrainedIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testValidatingConstrainedIterator (
      $query, $result, $asserts, $flags
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="it" assert="it" min="1" max="2">
            <value flag="val" assert="flag" pattern="/\d/"/>
          </iterator>
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  public function provideExactlyConstrainedIterator () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array (),
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array ( 1 ),
            null,
            Array ( 'it' ),
            Array ()
        ),
        Array (
            Array ( 1, 2 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 2, 3 ),
            Array ( 1, 2 ),
            Array (),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 'hello world' ),
            null,
            Array ( 'flag' => 'hello world', 'it' ),
            Array ()
        ),
        Array (
            Array ( 'hello', 'world' ),
            null,
            Array ( 'flag' => 'world', 'it' ),
            Array ()
        ),
// TODO
// Sets the last failing assert value
// it should probably be the first one or all of them
        Array (
            Array ( 'hello', 1, 'world' ),
            null,
            Array ( 'flag' => 'world', 'it' ),
            Array ()
        ),
        Array (
            Array ( 'hello', 1, 'world', 2 ),
            Array ( 1 => 1, 3 => 2 ),
            Array ( 'flag' => 'world' ),
            Array ( 'it', 'val' )
        ),
        Array (
            Array ( 1, 'hello', 2, 'world', 3 ),
            Array ( 0 => 1, 2 => 2 ),
            Array ( 'flag' => 'hello' ),
            Array ( 'it', 'val' )
        ),
    );
  }
  
  /**
   * @dataProvider provideExactlyConstrainedIterator
   *
   * @covers MapFilter_TreePattern_Tree_Iterator
   */
  public function testExactlyConstrainedIterator (
      $query, $result, $asserts, $flags
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="it" assert="it" min="2" max="2">
            <value flag="val" assert="flag" pattern="/\d/"/>
          </iterator>
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}

