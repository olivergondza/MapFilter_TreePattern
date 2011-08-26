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
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <key name="name">value</key>
        </pattern>
    ' );
  }
  
  /**
   *
   */
  public function testEmpty () {
  
    $a = MapFilter_TreePattern::load ( '
        <pattern><key name="name"/></pattern>
    ' );
    
    $b = MapFilter_TreePattern::load ( '
        <pattern><key name="name"></key></pattern>
    ' );
    
    $this->assertEquals ( $a, $b );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'key' node must have exactly one follower but 2 given.
   */
  public function testNotExactlyOneFollower () {
  
    MapFilter_TreePattern::load ( '
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

    $pattern = MapFilter_TreePattern::load ( '
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

    $pattern = MapFilter_TreePattern::load ( '
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

    $pattern = MapFilter_TreePattern::load ( '
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
  
  const NUMBER_OR_STRING_PATTERN = '
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
  
  public function provideKeyValue () {
  
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
            Array ( 'bool' => true ),
            Array (),
            Array (),
            Array ( 'number', 'string' ),
        ),
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

    );
  }
  
  /**
   * @dataProvider provideKeyValue
   *
   * @group Unit::TreePattern::Key::TestKeyValue
   */
  public function testKeyValue (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern::load ( self::NUMBER_OR_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
  }
  
  public function provideKeyValueArrayObject () {

    $data = Array ();
    foreach ( $this->provideKeyValue () as $set ) {

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
   * @dataProvider provideKeyValueArrayObject
   *
   * @group Unit::TreePattern::Key::TestKeyValueArrayObject
   */
  public function testKeyValueArrayObject (
      $query, $results, Array $flags, Array $asserts
  ) {
  
    $pattern = MapFilter_TreePattern::load ( self::NUMBER_OR_STRING_PATTERN );
    
    $result = $pattern->getFilter ( $query )->fetchResult ();

    $this->assertEquals ( $results, $result->getResults () );
    $this->assertSame ( $flags, $result->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $result->getAsserts ()->getAll () );
  }
}
