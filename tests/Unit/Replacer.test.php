<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 *
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
        Array ( '/^a$/b/', 'a', 'b' ),
        Array ( '/^a$/b/', 'c', 'c' ),
        Array ( '/^a$/b/', 'aa', 'aa' ),
        Array ( '/^a$/b/', '', '' ),
        Array ( 's/^a$/b/', 'a', 'b' ),
        Array ( 's/^a$/b/', 'c', 'c' ),
        Array ( 's/^a$/b/', 'aa', 'aa' ),
        Array ( 's/^a$/b/', '', '' ),
        Array ( 's/^\/$/b/', '/', 'b' ),
        Array ( 's/^\/$/b/', '!', '!' ),
        Array ( 's/^\/$/b/', '//', '//' ),
        Array ( 's/^\/$/b/', '', '' ),
    );
  }
  
  /**
   * @dataProvider      provideReplacement
   */
  public function testReplacement ( $replacement, $src, $expected ) {
  
    $rep = new MapFilter_TreePattern_Tree_Replacer ( $replacement );
    
    $this->assertSame (
        $expected,
        $rep->replace ( $src )
    );
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
