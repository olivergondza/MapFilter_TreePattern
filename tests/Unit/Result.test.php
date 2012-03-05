<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 *
 */
class MapFilter_Test_Unit_TreePattern_Result extends
    PHPUnit_Framework_TestCase
{

  public function setUp () {
  
    $this->_flags = new MapFilter_TreePattern_Flags;
    $this->_asserts = new MapFilter_TreePattern_Asserts;
  }

  public function provideResultAndValid () {
  
    return Array (
        Array ( 42, true ),
        Array ( null, false ),
        Array ( new StdClass, false ),
        Array ( Array ( 42 ), true ),
    );
  }

  /**
   * @dataProvider provideResultAndValid
   */
  public function testObtain ( $res, $valid ) {
  
    $result = new MapFilter_TreePattern_Result (
        $res, $this->_asserts, $this->_flags, $valid
    );
    
    $this->assertSame ( $res, $result->getResults () );
    $this->assertSame ( $this->_asserts, $result->getAsserts () );
    $this->assertSame ( $this->_flags, $result->getFlags () );
    $this->assertSame ( $valid, $result->isValid () );
  }
  
  public function testBuild () {
  
    $f1 = new MapFilter_TreePattern_Flags ( Array ( 'f1' ) );
    $a1 = new MapFilter_TreePattern_Asserts ( Array ( 'a1' ) );
    
    $f2 = new MapFilter_TreePattern_Flags;
    $a2 = new MapFilter_TreePattern_Asserts ( Array ( 'a2' ) );
    
    $f3 = new MapFilter_TreePattern_Flags ( Array ( 'f3' ) );
    $a3 = new MapFilter_TreePattern_Asserts;
    
    $builtResult = MapFilter_TreePattern_Result::builder()
        ->putFlags(null)
        ->putAsserts(null)
        ->putFlags($f1)
        ->putAsserts($a1)
        ->putFlags($f2)
        ->putAsserts($a2)
        ->putFlags($f3)
        ->putAsserts($a3)
        ->putFlags(null)
        ->putAsserts(null)
        ->build(Array('val'), true)
    ;
    
    $this->assertSame ( Array ( 'val' ), $builtResult->getResults () );
    $this->assertSame ( true, $builtResult->isValid () );
    $this->assertSame (
        Array ( 'a1', 'a2' ),
        $builtResult->getAsserts ()->getAll ()
    );
  }
}
