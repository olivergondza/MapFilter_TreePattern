<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Key
 */
class MapFilter_Test_Unit_TreePattern_Key extends PHPUnit_Framework_TestCase {

  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'key' has no content.
   */
  public function testTextContent () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name">value</key>
        </pattern>
    ' );
  }
  
  /**
   *
   */
  public function testEmpty () {
  
    $a = MapFilter_TreePattern_Xml::load ( '
        <pattern><key name="name"/></pattern>
    ' );
    
    $b = MapFilter_TreePattern_Xml::load ( '
        <pattern><key name="name"></key></pattern>
    ' );
    
    $this->assertEquals ( $a, $b );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'key' node must have exactly one follower but 2 given.
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
   */
  public function testKeyOnlyValid ( $query ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name" flag="valid" assert="invalid">
          </key>
        </pattern>
    ' );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $query, $result->getResults () );
    $this->assertSame ( Array ( 'valid' ), $result->getFlags ()->getAll () );
    $this->assertSame ( Array (), $result->getAsserts ()->getAll () );
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
   */
  public function testKeyOnlyInvalid ( $query ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="not_provided_name" flag="valid" assert="invalid">
          </key>
        </pattern>
    ' );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( null, $result->getResults () );
    $this->assertSame ( Array (), $result->getFlags ()->getAll () );
    $this->assertSame ( Array ( 'invalid' ), $result->getAsserts ()->getAll () );
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
   */
  public function testKeyOnlyRich ( $query, $results ) {

    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <key name="name" flag="valid" assert="invalid">
          </key>
        </pattern>
    ' );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( Array ( 'valid' ), $result->getFlags ()->getAll () );
    $this->assertSame ( Array (), $result->getAsserts ()->getAll () );
  }
  
  const NUMBER_OPT_STRING_PATTERN = '
      <pattern>
        <opt>
          <key name="number" flag="number" assert="number">
            <value pattern="/\d+/" />
          </key>
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
            Array ( 'number', 'string' ),
        ),
        Array (
            Array ( 'string' => 15 ),
            Array (),
            Array (),
            Array ( 'number', 'string' ),
        ),
    );
  }
  
  /**
   * @dataProvider provideOptKeyValue
   */
  public function testOptKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( self::NUMBER_OPT_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
  }
  
  public function provideOptKeyValueArrayObject () {

    $data = Array ();
    foreach ( $this->provideOptKeyValue () as $set ) {

      if ( is_array ( $set[ 0 ] ) ) {
      
        $set[ 0 ] = new ArrayObject ( $set[ 0 ] );
      }
      
      if ( is_array ( $set[ 1 ] ) ) {
      
        $set[ 1 ] = new ArrayObject ( $set[ 1 ] );
      }
      
      $data[] = $set;
    }
    
    return $data;
  }
  
  /**
   * @dataProvider provideOptKeyValueArrayObject
   */
  public function testOptKeyValueArrayObject (
      $query, $results, Array $flags, Array $asserts
  ) {

    $pattern = MapFilter_TreePattern_Xml::load ( self::NUMBER_OPT_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
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
            Array ( 'number' )
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
            Array ( 'number' )
        ),
        Array (
            Array ( 'number' => 42, 'string' => 42 ),
            null,
            Array (),
            Array ( 'string' )
        ),
        Array (
            Array ( 'number' => '', 'string' => 42 ),
            null,
            Array (),
            Array ( 'number' )
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
            Array ( 'number' )
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
   */
  public function testAndKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( self::NUMBER_AND_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
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
            Array ( 'number', 'string' )
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
            Array ( 'number' )
        ),
        Array (
            Array ( 'number' => 42, 'string' => 42 ),
            Array ( 'number' => 42 ),
            Array ( 'number' ),
            Array ( 'string' )
        ),
        Array (
            Array ( 'number' => '', 'string' => 42 ),
            null,
            Array (),
            Array ( 'number', 'string' )
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
            Array ( 'number', 'string' )
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
            Array ( 'number', 'string' )
        ),
        Array (
            Array ( 'string' => 42, 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' )
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
   */
  public function testSomeKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( self::NUMBER_SOME_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
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
            Array ( 'number', 'string' )
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
            Array ( 'number' )
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
            Array ( 'number', 'string' )
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
            Array ( 'number', 'string' )
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
            Array ( 'number', 'string' )
        ),
        Array (
            Array ( 'string' => 42, 'bool' => true ),
            null,
            Array (),
            Array ( 'number', 'string' )
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
   */
  public function testOneKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( self::NUMBER_ONE_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
  }
}
