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
  
  public function testCompose () {
  
    $f1 = new MapFilter_TreePattern_Flags ( Array ( 'f1' ) );
    $a1 = new MapFilter_TreePattern_Asserts ( Array ( 'a1' ) );
    $r1 = new MapFilter_TreePattern_Result ( null, $a1, $f1, true );
    
    $f2 = new MapFilter_TreePattern_Flags;
    $a2 = new MapFilter_TreePattern_Asserts ( Array ( 'a2' ) );
    $r2 = new MapFilter_TreePattern_Result ( 42, $a2, $f2, false );
    
    $f3 = new MapFilter_TreePattern_Flags ( Array ( 'f3' ) );
    $a3 = new MapFilter_TreePattern_Asserts;
    $r3 = new MapFilter_TreePattern_Result ( '', $a3, $f3, false );
    
    $flags = new MapFilter_TreePattern_Flags ( Array ( 'flag' ) );
    $asserts = new MapFilter_TreePattern_Asserts;
    $result = new MapFilter_TreePattern_Result (
        Array ( 'val' ), $asserts, $flags, true
    );
    
    $combinedResult = $result->combine ( Array ( $r1, $r2, $r3 ) );
    
    $this->assertSame ( Array ( 'val' ), $combinedResult->getResults () );
    $this->assertSame ( true, $combinedResult->isValid () );
    $this->assertSame (
        Array ( 'a1', 'a2' ),
        $combinedResult->getAsserts ()->getAll ()
    );
  }
}
