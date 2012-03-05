<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 *
 */
class MapFilter_Test_Unit_TreePattern_Flags extends
    PHPUnit_Framework_TestCase
{

  public function testBuild () {
  
    $in = Array ( 'gh', 'df', 'gh', 'as', 'as' );
  
    $atOnce = new MapFilter_TreePattern_Flags ( $in );

    $fluent = new MapFilter_TreePattern_Flags ();
    $fluent->set( 'gh' )->set( 'df' )->set( 'gh' )->set( 'as' )->set( 'as' );

    $this->assertEquals ( $atOnce, $fluent );
    
    $sorted = array_unique ( $in );
    sort ( $sorted );
    
    $this->assertEquals ( $sorted, $atOnce->getAll () );
    $this->assertEquals ( $sorted, $fluent->getAll () );
    
    foreach ( $in as $flagCandidate ) {
    
      $this->assertTrue ( $atOnce->exists ( $flagCandidate ) );
    }
  }
  
  public function testSelfCombine () {
  
    $in = Array ( 'f1', 'f2', 'f3' );
    $flags = new MapFilter_TreePattern_Flags ( $in );
    
    $this->assertEquals (
        $in,
        $flags->getAll()
    );
    
    $this->assertEquals (
        $in,
        $flags->combine(Array($flags))->getAll()
    );
  }
  
  public function testCombine () {
  
    $in1 = Array ( 'f1' );
    $in2 = Array ( 'f1', 'f2' );
    $in3 = Array ( 'f1', 'f2', 'f3' );
    
    $f1 = new MapFilter_TreePattern_Flags($in1);
    $f2 = new MapFilter_TreePattern_Flags($in2);
    $f3 = new MapFilter_TreePattern_Flags($in3);

    $emptyFlags = new MapFilter_TreePattern_Flags;
    $this->assertEquals (
        $in3,
        $emptyFlags->combine(Array($f1, $f2, $f3))->getAll ()
    );
    
    $chained = $emptyFlags->combine(Array($f1))
        ->combine(Array($f2))
        ->combine(Array($f3))
        ->getAll()
    ;
    
    $this->assertEquals ($in3, $chained);
    
    $this->assertEquals (
        $in3,
        $emptyFlags->combine(Array($f3, $f2, $f1))->getAll ()
    );
    
    $chained = $emptyFlags->combine(Array($f3))
        ->combine(Array($f2))
        ->combine(Array($f1))
        ->getAll()
    ;
    
    $this->assertEquals ($in3, $chained);
  }
}
