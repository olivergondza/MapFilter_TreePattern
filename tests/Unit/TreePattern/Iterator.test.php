<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Iterator
 */
class MapFilter_Test_Unit_TreePattern_Iterator extends PHPUnit_Framework_TestCase {

  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'iterator' has no content.
   */
  public function testTextContent () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator>value</iterator>
        </pattern>
    ' );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'iterator' node must have exactly one follower but 2 given.
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
  
  /**
   *
   */
  public function testEmpty () {
  
    $a = MapFilter_TreePattern_Xml::load ( '
        <pattern><iterator /></pattern>
    ' );
    
    $b = MapFilter_TreePattern_Xml::load ( '
        <pattern><iterator></iterator></pattern>
    ' );
    
    $this->assertEquals ( $a, $b );
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
            new ArrayIterator ( Array () ),
            new ArrayIterator ( Array () ),
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
            new ArrayIterator ( Array ( 'some', 'content' ) ),
            new ArrayIterator ( Array ( 'some', 'content' ) ),
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
   */
  public function testEmptyIterator ( $query, $result, $asserts, $flags ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <iterator flag="iterator" assert="no_iterator" />
        </pattern>
    ' );
    
    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
    ;
    
    $this->assertEquals ( $result, $actual->getResults () );
    $this->assertSame ( $asserts, $actual->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $actual->getFlags ()->getAll () );
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

    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
    ;

    $this->assertEquals ( $result, $actual->getResults () );
    $this->assertSame ( $asserts, $actual->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $actual->getFlags ()->getAll () );
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
            Array ( Array ( 'key2' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key1' => 8 ), Array ( 'key2' => 'invalid' ) ),
            Array ( Array ( 'key1' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key1' ),
        ),
        
        Array (
            Array ( Array ( 'key1' => 'invalid' ), Array ( 'key1' => 8 ) ),
            Array ( Array ( 'key1' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key1' => 8 ), Array ( 'key1' => 'invalid' ) ),
            Array ( Array ( 'key1' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key1' ),
        ),
        Array (
            Array ( Array ( 'key2' => 'invalid' ), Array ( 'key2' => 8 ) ),
            Array ( Array ( 'key2' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key2' ),
        ),
        Array (
            Array ( Array ( 'key2' => 8 ), Array ( 'key2' => 'invalid' ) ),
            Array ( Array ( 'key2' => 8 ) ),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator', 'key2' ),
        ),
        
        Array (
            Array (
                Array ( 'wring' => 1 ), Array ( 'key1' => 7 ),
                Array ( 'wring' => 2 ), Array ( 'key2' => 8 ),
                Array ( 'wring' => 3 )
            ),
            Array ( Array ( 'key1' => 7 ), Array ( 'key2' => 8 ) ),
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
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator' ),
        ),
        
        Array (
            Array ( Array ( 'key2' => 'invalid' ) ),
            Array (),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid', 'key2' => 'invalid' ) ),
            Array (),
            Array ( 'no_key1', 'no_key2' ),
            Array ( 'iterator' ),
        ),
        Array (
            Array ( Array ( 'key1' => 'invalid', 'key2' => 7 ) ),
            Array ( Array ( 'key2' => 7 ) ),
            Array ( 'no_key1' ),
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

    $actual = $pattern->getFilter ( $query )->fetchResult ();
    
    $this->assertSame ( $asserts, $actual->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $actual->getFlags ()->getAll () );
    
    $actualResults = ($actual->getResults () === null)
        ? null
        : array_values ( $actual->getResults () )
    ;
    
    $this->assertEquals ( $result, $actualResults );
  }
}
