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
    
    foreach ( $in as $flagCandidate ) {
    
      $this->assertTrue ( $atOnce->exists ( $flagCandidate ) );
    }
  }
}
