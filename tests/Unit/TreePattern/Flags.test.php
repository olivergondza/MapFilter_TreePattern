<?php
/**
 * Require tested class
 */
require_once PHP_TREEPATTERN_DIR . '/Flags.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Flags
 */
class MapFilter_Test_Unit_TreePattern_Flags extends
    PHPUnit_Framework_TestCase
{

  public static function testBuild () {
  
    $in = Array ( 'gh', 'df', 'gh', 'as', 'as' );
  
    $atOnce = new MapFilter_TreePattern_Flags ( $in );

    $fluent = new MapFilter_TreePattern_Flags ();
    $fluent->set( 'gh' )->set( 'df' )->set( 'gh' )->set( 'as' )->set( 'as' );

    self::assertEquals ( $atOnce, $fluent );
    
    $sorted = array_unique ( $in );
    sort ( $sorted );
    
    self::assertEquals ( $sorted, $atOnce->getAll () );
    
    foreach ( $in as $flagCandidate ) {
    
      self::assertTrue ( $atOnce->exists ( $flagCandidate ) );
    }
  }
}
