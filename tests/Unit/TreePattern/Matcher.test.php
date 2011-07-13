<?php
/**
 * Require tested class
 */
require_once PHP_TREEPATTERN_DIR . '/Tree/Matcher.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Matcher
 */
class MapFilter_Test_Unit_TreePattern_Matcher extends
    PHPUnit_Framework_TestCase
{
  
  public function provideUnsanitizedEquality () {
  
    return Array (
        Array ( 'hello', '/^hello$/' ),
        Array ( 'hell/o', '/^hell\/o$/' ),
        Array ( '/hello/m', '/hello/m' ),
        Array ( '/hello\/?/imsxeADSUXu', '/hello\/?/imsxeADSUXu' ),
        Array ( '?hello\?*?imsxeADSUXu', '?hello\?*?imsxeADSUXu' ),
        Array ( '`hello\`?`imsxeADSUXu', '`hello\`?`imsxeADSUXu' ),
        Array ( '!hello\!?!imsxeADSUXu', '!hello\!?!imsxeADSUXu' ),
        Array ( '@hello\@?@imsxeADSUXu', '@hello\@?@imsxeADSUXu' ),
        Array ( '#hello\#?#imsxeADSUXu', '#hello\#?#imsxeADSUXu' ),
        Array ( '$hello\$?$imsxeADSUXu', '$hello\$?$imsxeADSUXu' ),
        Array ( '%hello\%?%imsxeADSUXu', '%hello\%?%imsxeADSUXu' ),
        Array ( '^hello\^?^imsxeADSUXu', '^hello\^?^imsxeADSUXu' ),
        Array ( '&hello\&?&imsxeADSUXu', '&hello\&?&imsxeADSUXu' ),
        Array ( '*hello\*?*imsxeADSUXu', '*hello\*?*imsxeADSUXu' ),
        Array ( '+hello\+?+imsxeADSUXu', '+hello\+?+imsxeADSUXu' ),
        Array ( '-hello\-?-imsxeADSUXu', '-hello\-?-imsxeADSUXu' ),
        Array ( ';hello\;?;imsxeADSUXu', ';hello\;?;imsxeADSUXu' ),
        Array ( ',hello\,?,imsxeADSUXu', ',hello\,?,imsxeADSUXu' ),
        Array ( '.hello\.?.imsxeADSUXu', '.hello\.?.imsxeADSUXu' ),
        Array ( '', '/^$/' ),
        Array ( '^hello$', '/^^hello$$/' ),
        Array ( '///', '///' ),
    );
  }
  
  /**
   * @dataProvider      provideUnsanitizedEquality
   */
  public function testUnsanitizedEquality ( $sanitized, $unsanitized ) {
  
    $sanitized = new MapFilter_TreePattern_Tree_Matcher ( $sanitized );
    $unsanitized = new MapFilter_TreePattern_Tree_Matcher ( $unsanitized );

    $this->assertEquals ( $sanitized, $unsanitized );
    
    $this->assertEquals (
        $sanitized->match ( 'hello' ),
        $unsanitized->match ( 'hello' )
    );
  }
}
