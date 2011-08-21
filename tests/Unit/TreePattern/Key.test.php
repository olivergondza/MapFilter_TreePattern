<?php
/**
 * Require tested class
 */
require_once PHP_TREEPATTERN_CLASS;

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Key
 */
class MapFilter_Test_Unit_TreePattern_Key extends
    PHPUnit_Framework_TestCase
{

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
  public function testKeyOnlyValid ( $data ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <key name="name" flag="valid" assert="invalid">
          </key>
        </pattern>
    ' );
    
    $filter = new MapFilter ( $pattern, $data );

    $this->assertEquals ( $data, $filter->fetchResult ()->getResults () );
    $this->assertSame ( Array ( 'valid' ), $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( Array (), $filter->fetchResult ()->getAsserts ()->getAll () );
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
  public function testKeyOnlyInvalid ( $data ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <key name="not_provided_name" flag="valid" assert="invalid">
          </key>
        </pattern>
    ' );
    
    $filter = new MapFilter ( $pattern, $data );

    $this->assertEquals ( null, $filter->fetchResult ()->getResults () );
    $this->assertSame ( Array (), $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( Array ( 'invalid' ), $filter->fetchResult ()->getAsserts ()->getAll () );
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
    
    $filter = new MapFilter ( $pattern, $query );

    $this->assertEquals ( $results, $filter->fetchResult ()->getResults () );
    $this->assertSame ( Array ( 'valid' ), $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( Array (), $filter->fetchResult ()->getAsserts ()->getAll () );
  }
  
  public function provideKeyValue () {
  
    return Array (
        Array (
            null,
            null,
            Array (),
            Array ( 'number', 'string' )
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'number', 'string' )
        ),
        Array (
            new ArrayObject ( Array () ),
            new ArrayObject ( Array () ),
            Array (),
            Array ( 'number', 'string' )
        ),
    );
  }
  
  /**
   * @dataProvider provideKeyValue
   */
  public function testKeyValue ( $query, $results, $flags, $asserts ) {
  
    $pattern = MapFilter_TreePattern::load ( '
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
    ' );
    
    $filter = new MapFilter ( $pattern, $query );

    $this->assertEquals ( $results, $filter->fetchResult ()->getResults () );
    $this->assertSame ( $flags, $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $filter->fetchResult ()->getAsserts ()->getAll () );
  }
}
