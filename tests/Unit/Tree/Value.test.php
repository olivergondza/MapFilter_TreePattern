<?php

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 * @covers MapFilter_TreePattern_Tree_Value
 * @covers MapFilter_TreePattern_Tree_Value_Builder
 */
class MapFilter_Test_Unit_TreePattern_Value extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideValuePattern () {
  
    $two = new StringClass ( '2' );
    $word = new StringClass ( 'word' );
    $mf = new MapFilter;
  
    return Array (
        Array ( 1, 1, Array ( 'flag' ) ),
        Array ( '1', '1', Array ( 'flag' ) ),
        Array ( 'asdf', null, Array (), Array ( 'assert' => 'asdf' ) ),
        Array ( $two, $two, Array ( 'flag' ) ),
        Array ( $word, null, Array (), Array ( 'assert' => $word ) ),
        Array ( $mf, null, Array (), Array ( 'assert' => $mf ) ),
    );
  }

  /**
   * @dataProvider      provideValuePattern
   */
  public function testValuePattern (
      $string, $result, $flags, $asserts = Array ()
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <!-- [TreePattern_Regex] -->
        <pattern>
          <value pattern="/\d+/" flag="flag" assert="assert" />
        </pattern>
        <!-- [TreePattern_Regex] -->
    ' );

    $this->assertResultsEquals ( $pattern, $string, $result, $asserts, $flags );
  }
  
  public function provideValueReplacement () {
  
    $twelve = new StringClass( '12' );
    $word = new StringClass( 'word' );
  
    $filter = new MapFilter;
  
    return Array (
        Array ( 1, '_', Array ( 'flag' ) ),
        Array ( '1', '_', Array ( 'flag' ) ),
        Array ( 'asdf', 'asdf', Array ( 'flag' ) ),
        Array ( $twelve, '_', Array ( 'flag' ) ),
        Array ( $word, 'word', Array ( 'flag' ) ),
        Array ( $filter, $filter, Array ( 'flag' ) ),
    );
  }
  
  /**
   * @dataProvider      provideValueReplacement
   */
  public function testValueReplacement ( $string, $result, $flags ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <!-- [TreePattern_Replacement] -->
        <pattern>
          <value replacement="/(\d)+/_/" flag="flag" assert="assert" />
        </pattern>
        <!-- [TreePattern_Replacement] -->
    ' );
    
    $this->assertResultsEquals ( $pattern, $string, $result, Array (), $flags );
  }
  
  public function provideDefault () {
  
    return Array (
        Array ( Array (), 'value', Array ( 'flag' ) ),
        Array ( null, 'value', Array ( 'flag' ) ),
        Array ( '1', '1', Array ( 'flag' ) ),
        Array ( 'a1b', 'a1b', Array ( 'flag' ) ),
        Array ( 'a 1 b', 'a1b', Array ( 'flag' ) ),
        Array ( ' ', 'value', Array ( 'flag' ) )
    );
  }
  
  /**
   * @dataProvider      provideDefault
   */
  public function testDefault (
      $string, $result, $flags, $asserts = Array ()
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <value pattern="/\d+/" default="val ue" replacement="/\s//"
              flag="flag" assert="assert"
          />
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, $string, $result, $asserts, $flags );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'value' has no content.
   *
   * @covers MapFilter_TreePattern_Tree_InvalidContentException
   * @covers MapFilter_TreePattern_Tree_Value_Builder<extended>
   */
  public function testTextContent () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <value>value</value>
        </pattern>
    ' );
  }
  
  public function provideCascadingValues () {
  
    return Array (
        Array ( '////', null, Array (), Array ( 'invalid' => '////' ) ),
        Array ( '12_34', '1234', Array ( 'valid' ) ),
        Array ( '111', null, Array (), Array ( 'invalid' => '111', 'bad_length' => '111' ) ),
        Array ( '0123456789abcdef', '0123456789abcdef', Array ( 'valid' ) ),
        Array ( '0_____0_00', '0000', Array ( 'valid' ) ),
        Array ( 'Dead_Beaf', 'DeadBeaf', Array ( 'valid' ) ),
    );
  }
  
  /**
   * @dataProvider      provideCascadingValues
   */
  public function testCascadingValues (
      $query, $result, Array $flags = Array (), Array $asserts = Array ()
  ) {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <!-- [TreePattern_Cascading] -->
        <pattern>
          <value pattern="/^[_0-9a-f]*$/i" replacement="/_//" assert="invalid">
            <value pattern="/^(..)+$/" assert="bad_length" flag="valid" />
          </value>
        </pattern>
        <!-- [TreePattern_Cascading] -->
    ' );
      
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  /**
   *
   */
  public function testPolicy () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <value pattern="/^\d+$/" assert="not a number">
            <one>
              <value pattern="/[02468]$/" flag="odd"  />
              <value pattern="/[13579]$/" flag="even" />
            </one>
          </value>
        </pattern>
    ' );
    
    $this->assertResultsEquals ( $pattern, '32', '32', Array (), Array ( 'odd' ) );
    $this->assertResultsEquals ( $pattern, '21', '21', Array (), Array ( 'even' ) );
    $this->assertResultsEquals ( $pattern, 'NaN', null, Array ( 'not a number' => 'NaN' ) );
  }
  
  /**
   *
   */
  public function testEmpty () {
  
    $a = MapFilter_TreePattern_Xml::load ( '
        <pattern><value pattern="asdf" /></pattern>
    ' );
    
    $b = MapFilter_TreePattern_Xml::load ( '
        <pattern><value pattern="asdf"></value></pattern>
    ' );
    
    $this->assertEquals ( $a, $b );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'value' node must have exactly one follower but 2 given.
   */
  public function testNotExactlyOneFollower () {
  
    MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <value pattern="asdf">
            <all />
            <opt />
          </value>
        </pattern>
    ' );
  }
  
  public function provideMap () {
  
    return Array (
        Array ( Array (), Array (), Array ( 'key' ) ),
        Array ( Array ( 'somethingElse' => 'node' ), Array (), Array ( 'key' ) ),
        Array ( Array ( 'node' => 'node' ), Array (), Array ( 'val' ) ),
        Array ( Array ( 'node' => 'asdf' ), Array ( 'node' => 'asdf' ) ),
    );
  }
}

class StringClass {

  private $_Str = null;

  public function __construct ( $str ) {
  
    assert ( is_string ( $str ) );
    
    $this->_str = $str;
  }
  
  public function __toString () {
  
    return $this->_str;
  }
}
