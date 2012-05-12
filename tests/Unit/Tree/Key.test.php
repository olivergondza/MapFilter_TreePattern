<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Key
 * @covers MapFilter_TreePattern_Tree_Key_Builder
 */
class MapFilter_Test_Unit_TreePattern_Key extends
    MapFilter_TreePattern_Test_Functional
{

  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'key' has no content.
   *
   * @covers MapFilter_TreePattern_Tree_InvalidContentException
   * @covers MapFilter_TreePattern_Tree_Key_Builder<extended>
   */
  public function testTextContent () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name">value</key>
        </pattern>
    ' );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'key' node must have exactly one follower but 2 given.
   *
   * @covers MapFilter_TreePattern_NotExactlyOneFollowerException
   */
  public function testNotExactlyOneFollower () {
  
    MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name">
            <all />
            <opt />
          </key>
        </pattern>
    ' );
  }
  
  public function provideKeyOnlyValid () {
  
    return Array (
        Array (
            Array ( 'name' => null ),
        ),
        Array (
            Array ( 'name' => 'name' ),
        ),
        Array (
            Array ( 'name' => 7 ),
        ),
        Array (
            Array ( 'name' => Array ( 'no' => 'name' ) ),
        ),
        Array (
            new ArrayObject (
                Array ( 'name' => 'name' )
            ),
        ),
        Array (
            Array ( 'name' => new ArrayObject (
                Array ( 'no' => 'name' )
            ) ),
        ),
        Array (
            new ArrayObject (
                Array ( 'name' => new ArrayObject (
                    Array ( 'no' => 'name' )
                ) )
            ),
        ),
    );
  }
  
  /**
   * @dataProvider provideKeyOnlyValid
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testKeyOnlyValid ( $query ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name" flag="valid" assert="invalid" />
        </pattern>
    ' );
    
    $this->assertResultsEquals (
        $pattern, $query, $query, Array (), Array ( 'valid' )
    );
  }
  
  public function provideKeyOnlyInvalid () {
  
    return Array (
        Array ( 7 ),
        Array ( 'name' ),
        Array (
            Array ( 'name' => null )
        ),
        Array (
            Array ( 'name' => 'name' )
        ),
        Array (
            Array ( 'name' => 7 )
        ),
        Array (
            Array ( 'name' => Array ( 'no' => 'name' ) )
        ),
        Array ( new ArrayObject (
            Array ( 'name' => 'name' )
        ) ),
        Array (
            Array ( 'name' => new ArrayObject (
                Array ( 'no' => 'name' )
            ) ),
        ),
        Array ( new ArrayObject (
                Array ( 'name' => new ArrayObject (
                    Array ( 'no' => 'name' )
                ) )
        ) ),
    );
  }
  
  /**
   * @dataProvider provideKeyOnlyInvalid
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testKeyOnlyInvalid ( $query ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="not_provided_name" flag="valid" assert="invalid" />
        </pattern>
    ' );
    
    $this->assertResultsEquals (
        $pattern, $query, null, Array ( 'invalid' )
    );
  }
  
  public function provideKeyOnlyRich () {
  
    return Array (
        Array (
            Array ( 'name' => null, '____' => null ),
            Array ( 'name' => null ),
        ),
        Array (
            new ArrayObject ( Array ( 'name' => null, '____' => null ) ),
            new ArrayObject ( Array ( 'name' => null ) ),
        ),
        Array (
            Array ( 'name' => 'val', '____' => 'val' ),
            Array ( 'name' => 'val' ),
        ),
        Array (
            new ArrayObject ( Array ( 'name' => 'val', '____' => 'val' ) ),
            new ArrayObject ( Array ( 'name' => 'val' ) ),
        ),
    );
  }
  
  /**
   * @dataProvider provideKeyOnlyRich
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testKeyOnlyRich ( $query, $results ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name" flag="valid" assert="invalid" />
        </pattern>
    ' );
    
    $this->assertResultsEquals (
        $pattern, $query, $results, Array (), Array ( 'valid' )
    );
  }
  
  const NUMBER_OPT_STRING_PATTERN = '
      <pattern>
        <opt>
          <!-- TreePattern_Key__ -->
          <key name="number" flag="number" assert="number">
            <value pattern="/\d+/" />
          </key>
          <!-- __TreePattern_Key -->
          <key name="string" flag="string" assert="string">
            <value pattern="/[a-z]+/" />
          </key>
        </opt>
      </pattern>
  ';

  public function provideOptKeyValue () {
  
    return Array (
        /** Empty */
        Array (
            null,
            null,
            Array (),
            Array ( 'number', 'string' ),
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'number', 'string' ),
        ),
        /** Valid keys */
        Array (
            Array ( 'number' => 0 ),
            Array ( 'number' => 0 ),
            Array ( 'number' ),
            Array ( 'string' ),
        ),
        Array (
            Array ( 'string' => 'asdf' ),
            Array ( 'string' => 'asdf' ),
            Array ( 'string' ),
            Array ( 'number' ),
        ),
        Array (
            Array ( 'string' => 'asdf', 'number' => 0 ),
            Array ( 'string' => 'asdf', 'number' => 0 ),
            Array ( 'number', 'string' ),
            Array (),
        ),
        /** Redundant keys */
        Array (
            Array ( 'bool' => true ),
            Array (),
            Array (),
            Array ( 'number', 'string' ),
        ),
        Array (
            Array ( 'string' => 'asdf', 'number' => 0, 'bool' => true ),
            Array ( 'string' => 'asdf', 'number' => 0 ),
            Array ( 'number', 'string' ),
            Array (),
        ),
        /** Errors */
        Array (
            Array ( 'number' => 'asdf' ),
            Array (),
            Array (),
            Array ( 'number' => 'asdf', 'string' ),
        ),
        Array (
            Array ( 'string' => 15 ),
            Array (),
            Array (),
            Array ( 'number', 'string' => 15 ),
        ),
    );
  }
  
  /**
   * @dataProvider provideOptKeyValue
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testOptKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load (
        self::NUMBER_OPT_STRING_PATTERN
    );
    
    $this->assertResultsEquals ( $pattern, $query, $results, $asserts, $flags );
  }

  const NUMBER_AND_STRING_PATTERN = '
      <pattern>
        <all>
          <key name="number" flag="number" assert="number">
            <value pattern="/\d+/" />
          </key>
          <key name="string" flag="string" assert="string">
            <value pattern="/[a-z]+/" />
          </key>
        </all>
      </pattern>
  ';
  
  public function provideAndKeyValue () {
  
    return Array (
        /** Empty */
        Array (
            null,
            null,
            Array (),
            Array ( 'number' ),
        ),
        Array (
            Array (),
            null,
            Array (),
            Array ( 'number' ),
        ),
        /** One */
        Array (
            Array ( 'number' => 42 ),
            null,
            Array (),
            Array ( 'string' )
        ),
        Array (
            Array ( 'string' => 'str' ),
            null,
            Array (),
            Array ( 'number' )
        ),
        Array (
            Array ( 'number' => 'not a number' ),
            null,
            Array (),
            Array ( 'number' => 'not a number' )
        ),
        /** Both */
        Array (
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number', 'string' ),
            Array ()
        ),
        Array (
            Array ( 'number' => 42, 'string' => 'str', 'something' => 'else' ),
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number', 'string' ),
            Array ()
        ),
        Array (
            Array ( 'number' => '', 'string' => 'str' ),
            null,
            Array (),
            Array ( 'number' => '' )
        ),
        Array (
            Array ( 'number' => 42, 'string' => 42 ),
            null,
            Array (),
            Array ( 'string' => 42 )
        ),
        Array (
            Array ( 'number' => '', 'string' => 42 ),
            null,
            Array (),
            Array ( 'number' => '' )
        ),
        /** Redundant Keys */
        Array (
            Array ( 'bool' => true ),
            null,
            Array (),
            Array ( 'number' )
        ),
        Array (
            Array ( 'number' => '', 'bool' => true ),
            null,
            Array (),
            Array ( 'number' => '' )
        ),
        Array (
            Array ( 'number' => 42, 'bool' => true ),
            null,
            Array (),
            Array ( 'string' )
        ),
        Array (
            Array ( 'string' => '', 'bool' => true ),
            null,
            Array (),
            Array ( 'number' )
        ),
        Array (
            Array ( 'string' => 42, 'bool' => true ),
            null,
            Array (),
            Array ( 'number' )
        ),
        Array (
            Array ( 'string' => 'str', 'number' => 42, 'bool' => true ),
            Array ( 'string' => 'str', 'number' => 42 ),
            Array ( 'number', 'string' ),
            Array ()
        ),
    );
  }
  
  /**
   * @dataProvider provideAndKeyValue
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testAndKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( self::NUMBER_AND_STRING_PATTERN );
    
    $this->assertResultsEquals ( $pattern, $query, $results, $asserts, $flags );
  }
  
  const NUMBER_SOME_STRING_PATTERN = '
      <pattern>
        <some>
          <key name="number" flag="number" assert="number">
            <value pattern="/\d+/" />
          </key>
          <key name="string" flag="string" assert="string">
            <value pattern="/[a-z]+/" />
          </key>
        </some>
      </pattern>
  ';
  
  public function provideSomeKeyValue () {
  
    return Array (
        /** Empty */
        Array (
            null,
            null,
            Array (),
            Array ( 'number', 'string' ),
        ),
        Array (
            Array (),
            null,
            Array (),
            Array ( 'number', 'string' ),
        ),
        /** One */
        Array (
            Array ( 'number' => 42 ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ( 'string' )
        ),
        Array (
            Array ( 'string' => 'str' ),
            Array ( 'string' => 'str' ),
            Array ( 'string' ),
            Array ( 'number' )
        ),
        Array (
            Array ( 'number' => 'not a number' ),
            null,
            Array (),
            Array ( 'number' => 'not a number', 'string' )
        ),
        /** Both */
        Array (
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number', 'string' ),
            Array ()
        ),
        Array (
            Array ( 'number' => 42, 'string' => 'str', 'something' => 'else' ),
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number', 'string' ),
            Array ()
        ),
        Array (
            Array ( 'number' => '', 'string' => 'str' ),
            Array ( 'string' => 'str' ),
            Array ( 'string' ),
            Array ( 'number' => '' )
        ),
        Array (
            Array ( 'number' => 42, 'string' => 42 ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ( 'string' => 42 )
        ),
        Array (
            Array ( 'number' => '', 'string' => 42 ),
            null,
            Array (),
            Array ( 'number' => '', 'string' => 42 )
        ),
        /** Redundant Keys */
        Array (
            Array ( 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' )
        ),
        Array (
            Array ( 'number' => '', 'bool' => true ),
            null,
            Array (),
            Array ( 'number' => '', 'string' )
        ),
        Array (
            Array ( 'number' => 42, 'bool' => true ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ( 'string' )
        ),
        Array (
            Array ( 'string' => '', 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' => '' )
        ),
        Array (
            Array ( 'string' => 42, 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' => 42 )
        ),
        Array (
            Array ( 'string' => 'str', 'number' => 42, 'bool' => true ),
            Array ( 'string' => 'str', 'number' => 42 ),
            Array ( 'number', 'string' ),
            Array ()
        ),
    );
  }
  
  /**
   * @dataProvider provideSomeKeyValue
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testSomeKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load (
        self::NUMBER_SOME_STRING_PATTERN
    );

    $this->assertResultsEquals ( $pattern, $query, $results, $asserts, $flags );
  }
  
  const NUMBER_ONE_STRING_PATTERN = '
      <pattern>
        <one>
          <key name="number" flag="number" assert="number">
            <value pattern="/\d+/" />
          </key>
          <key name="string" flag="string" assert="string">
            <value pattern="/[a-z]+/" />
          </key>
        </one>
      </pattern>
  ';
  
  public function provideOneKeyValue () {
  
    return Array (
        /** Empty */
        Array (
            null,
            null,
            Array (),
            Array ( 'number', 'string' ),
        ),
        Array (
            Array (),
            null,
            Array (),
            Array ( 'number', 'string' ),
        ),
        /** One */
        Array (
            Array ( 'number' => 42 ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ()
        ),
        Array (
            Array ( 'string' => 'str' ),
            Array ( 'string' => 'str' ),
            Array ( 'string' ),
            Array ( 'number' )
        ),
        Array (
            Array ( 'number' => 'not a number' ),
            null,
            Array (),
            Array ( 'number' => 'not a number', 'string' )
        ),
        /** Both */
        Array (
            Array ( 'number' => 42, 'string' => 'str' ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ()
        ),
        Array (
            Array ( 'number' => 42, 'string' => 'str', 'something' => 'else' ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ()
        ),
        Array (
            Array ( 'number' => '', 'string' => 'str' ),
            Array ( 'string' => 'str' ),
            Array ( 'string' ),
            Array ( 'number' => '' )
        ),
        Array (
            Array ( 'number' => 42, 'string' => 42 ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ()
        ),
        Array (
            Array ( 'number' => '', 'string' => 42 ),
            null,
            Array (),
            Array ( 'number' => '', 'string' => 42 )
        ),
        /** Redundant Keys */
        Array (
            Array ( 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' )
        ),
        Array (
            Array ( 'number' => '', 'bool' => true ),
            null,
            Array (),
            Array ( 'number' => '', 'string' )
        ),
        Array (
            Array ( 'number' => 42, 'bool' => true ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ()
        ),
        Array (
            Array ( 'string' => '', 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' => '' )
        ),
        Array (
            Array ( 'string' => 42, 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' => 42 )
        ),
        Array (
            Array ( 'string' => 'str', 'number' => 42, 'bool' => true ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ()
        ),
    );
  }
  
  /**
   * @dataProvider provideOneKeyValue
   *
   * @covers MapFilter_TreePattern_Tree_Key
   */
  public function testOneKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load (
        self::NUMBER_ONE_STRING_PATTERN
    );
    
    $this->assertResultsEquals ( $pattern, $query, $results, $asserts, $flags );
  }
  
  public function provideNestedKey () {
  
    return Array (
        Array (
            null,
            null,
            Array ( 'no_outer' )
        ),
        Array (
            Array ( 'inner' => null ),
            null,
            Array ( 'no_outer' )
        ),
        Array (
            Array ( 'inner' => Array ( 'inner' => null ) ),
            null,
            Array ( 'no_outer' )
        ),
        Array (
            Array ( 'outer' => null ),
            null,
            Array ( 'no_inner', 'no_outer' )
        ),
        Array (
            Array ( 'outer' => Array () ),
            null,
            Array ( 'no_inner', 'no_outer' => Array () )
        ),
        Array (
            Array ( 'outer' => Array ( 'inner' => '' ) ),
            null,
            Array ( 'no_inner' => '', 'no_outer' => Array ( 'inner' => '' ) )
        ),
        Array (
            Array ( 'outer' => Array ( 'inner' => 'val' ) ),
            Array ( 'outer' => Array ( 'inner' => 'val' ) ),
            Array ()
        ),
        Array (
            Array ( 'outer' => Array ( 'inner' => 'val' ), 'other' => null ),
            Array ( 'outer' => Array ( 'inner' => 'val' ) ),
            Array ()
        ),
        Array (
            Array ( 'outer' => Array ( 'inner' => 'val', 'other' => null ) ),
            Array ( 'outer' => Array ( 'inner' => 'val' ) ),
            Array ()
        ),
        
    );
  }
  
  /**
   * @dataProvider provideNestedKey
   */
  public function testNestedKey (
      $query, $results, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <!-- TreePattern_NestedKey__ -->
        <pattern>
          <key name="outer" assert="no_outer">
            <key name="inner" assert="no_inner">
              <value pattern="/./" />
            </key>
          </key>
        </pattern>
        <!-- __TreePattern_NestedKey -->
    ' );
    
    $this->assertResultsEquals ( $pattern, $query, $results, $asserts );
  }
}
