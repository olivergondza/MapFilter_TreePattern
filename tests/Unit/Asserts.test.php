<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 *
 */
class MapFilter_Test_Unit_TreePattern_Asserts extends
    PHPUnit_Framework_TestCase
{

  public function testBuild () {
  
    $in = Array ( 'gh', 'df', 'gh', 'as', 'as' );
  
    $atOnce = new MapFilter_TreePattern_Asserts ( $in );

    $fluent = new MapFilter_TreePattern_Asserts ();
    $fluent->set( 'gh' )->set( 'df' )->set( 'gh' )->set( 'as' )->set( 'as' );

    $this->assertEquals ( $atOnce, $fluent );
    
    $sorted = array_unique ( $in );
    sort ( $sorted );
    
    $this->assertEquals ( $sorted, $atOnce->getAll () );
    
    foreach ( $in as $flagCandidate ) {
    
      $this->assertTrue ( $atOnce->exists ( $flagCandidate ) );
    }
  }
  
  public function testRich () {
  
    $value = 42;
  
    $in = Array (
        'as',
        'df' => Array (
            MapFilter_TreePattern_Asserts::PATH => 'myPath'
        ),
        'gh' => Array (
            MapFilter_TreePattern_Asserts::PATH => '',
            MapFilter_TreePattern_Asserts::VALUE => &$value
        )
    );
    
    $atOnce = new MapFilter_TreePattern_Asserts ( $in );

    $fluent = new MapFilter_TreePattern_Asserts ();
    $fluent->set ( 'as' )->set ( 'df', 'myPath' )->set ( 'gh', '', $value );

    $this->assertEquals ( $atOnce, $fluent );
    
    $this->assertEquals ( $atOnce->getPath ( 'df' ), $fluent->getPath ( 'df' ) );
    $this->assertEquals ( $atOnce->getPath ( 'gh' ), $fluent->getPath ( 'gh' ) );
    
    $this->assertSame ( $atOnce->getValue ( 'gh' ), $fluent->getValue ( 'gh' ) );
  }
  
  public function provideInvalidInit () {
  
    return Array (
        Array (
            Array ( 1 => 1 ),
        ),
        Array (
            Array ( Array () ),
        ),
        Array (
            Array ( Array ( 'str' ) ),
        ),
    );
  }
  
  /**
   * @dataProvider              provideInvalidInit
   * @expectedException         MapFilter_TreePattern_Asserts_InvalidInitializationException
   * @expectedExceptionMessage  Invalid format for assertion initialization
   */
  public function testInvalidInit ( $init ) {
  
    new MapFilter_TreePattern_Asserts ( $init );
  }
  
  public function provideMissingProperty () {
  
    return Array (
        Array ( 'getPath', 'plain' ),
        Array ( 'getValue', 'plain' ),
        Array ( 'getPath', 'void' ),
        Array ( 'getValue', 'void' ),
        Array ( 'getValue', 'path' )
    );
  }
  
  /**
   * @dataProvider              provideMissingProperty
   * @expectedException         MapFilter_TreePattern_Asserts_MissingPropertyException
   */
  public function testMissingProperty ( $property, $assert ) {
  
    $asserts = new MapFilter_TreePattern_Asserts ();
    $asserts->set ( 'plain' )->set ( 'path', 'key' );
    
    $asserts->$property ( $assert );
  }
}
