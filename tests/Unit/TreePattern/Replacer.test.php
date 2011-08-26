<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Replacer
 */
class MapFilter_Test_Unit_TreePattern_Replacer extends
    PHPUnit_Framework_TestCase
{
  
  public function provideInvalidReplacement () {
  
    return Array (
        Array ( '' ),
        Array ( '/^asdf$/' ),
        Array ( 's/^asdf$/' ),
        Array ( '^asdf$/asdf' ),
        Array ( '/^asdf$/asdf' ),
        Array ( '^asdf$/asdf/' ),
        Array ( '/^asdf$/ghjk/lm/' ),
        Array ( 'asdf/asdf' ),
        Array ( '////' ),
        Array ( '/^$///' ),
        Array ( '/^/$//' ),
    );
  }
  
  /**
   * @dataProvider      provideInvalidReplacement
   * @expectedException MapFilter_TreePattern_Tree_Replacer_InvalidStructureException
   */
  public function testInvalidReplacement ( $replacement ) {
  
    new MapFilter_TreePattern_Tree_Replacer ( $replacement );
  }
  
  public function provideReplacement () {
  
    return Array (
        Array ( '/^a$/b/' ),
        Array ( 's/^a$/b/' ),
        Array ( 's/^\/$/b/' ),
    );
  }
  
  /**
   * @dataProvider      provideReplacement
   */
  public function testReplacement ( $replacement ) {
  
    new MapFilter_TreePattern_Tree_Replacer ( $replacement );
  }
  
  /**
   *
   */
  public function testSlashReplacement () {
  
    $rep = new MapFilter_TreePattern_Tree_Replacer (
        '/\/{2,}/\//'
    );
    
    $this->assertEquals (
        '/proc/self',
        $rep->replace ( '/proc//self' )
    );
  }
}
