<?php
/**
 * Require tested class
 */
require_once PHP_TREEPATTERN_CLASS;

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Value
 */
class MapFilter_Test_Unit_TreePattern_Value extends
    PHPUnit_Framework_TestCase
{

  public function provideValuePattern () {
  
    $two = new StringClass( '2' );
    $word = new StringClass( 'word' );
  
    return Array (
        Array ( 1, 1, Array ( 'flag' ) ),
        Array ( '1', '1', Array ( 'flag' ) ),
        Array ( 'asdf', null, Array (), Array ( 'assert' ) ),
        Array ( $two, $two, Array ( 'flag' ) ),
        Array ( $word, null, Array (), Array ( 'assert' ) ),
        Array ( new MapFilter, null, Array (), Array ( 'assert' ) ),
    );
  }

  /**
   * @dataProvider      provideValuePattern
   */
  public function testValuePattern (
      $string, $result, $flags, $asserts = Array ()
  ) {
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <value pattern="/\d+/" flag="flag" assert="assert" />
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern, $string );
    
    $this->assertSame ( $result, $filter->fetchResult ()->getResults () );
    $this->assertSame ( $flags, $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $filter->fetchResult ()->getAsserts ()->getAll () );
  }
  
  public function provideValueReplacement () {
  
    $twelve = new StringClass( '12' );
    $word = new StringClass( 'word' );
  
    $filter = new MapFilter;
  
    return Array (
        Array ( 1, '2', Array ( 'flag' ) ),
        Array ( '1', '2', Array ( 'flag' ) ),
        Array ( 'asdf', 'asdf', Array ( 'flag' ) ),
        Array ( $twelve, '22', Array ( 'flag' ) ),
        Array ( $word, 'word', Array ( 'flag' ) ),
        Array ( $filter, $filter, Array ( 'flag' ) ),
    );
  }
  
  /**
   * @dataProvider      provideValueReplacement
   */
  public function testValueReplacement ( $string, $result, $flags ) {
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <value replacement="/1/2/" flag="flag" assert="assert" />
        </pattern>
    ' );
    
    $filter = new MapFilter ( $pattern, $string );
    
    $this->assertSame ( $result, $filter->fetchResult ()->getResults () );
    $this->assertSame ( $flags, $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( Array (), $filter->fetchResult ()->getAsserts ()->getAll () );
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
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <value pattern="/\d+/" default="val ue" replacement="/\s//"
              flag="flag" assert="assert"
          />
        </pattern>
    ' );
    
    $filter = new MapFilter ( $pattern, $string );
    
    $this->assertSame ( $result, $filter->fetchResult ()->getResults () );
    $this->assertSame ( $flags, $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( $asserts, $filter->fetchResult ()->getAsserts ()->getAll () );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'value' has no content.
   */
  public function testTextContent () {
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <value>value</value>
        </pattern>
    ' );
  }
  
  public function provideCascadingValues () {
  
    return Array (
        Array ( '////', null, Array (), Array ( 'content' ) ),
        Array ( '12_34', '1234', Array ( 'valid' ) ),
        Array ( '111', null, Array (), Array ( 'content', 'length' ) ),
        Array ( '0123456789abcdef', '0123456789abcdef', Array ( 'valid' ) ),
        Array ( '0_____0_00', '0000', Array ( 'valid' ) )
    );
  }
  
  /**
   * @dataProvider      provideCascadingValues
   */
  public function testCascadingValues (
      $data, $result, Array $flags = Array (), Array $asserts = Array ()
  ) {
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <value pattern="/^[_0-9a-f]*$/" replacement="/_//" assert="content">
            <value pattern="/^(..)+$/" assert="length" flag="valid" />
          </value>
        </pattern>
    ' );
    
    $filter = new MapFilter ( $pattern, $data );
    
    $this->assertSame ( $asserts, $filter->fetchResult ()->getAsserts ()->getAll () );
    $this->assertSame ( $flags, $filter->fetchResult ()->getFlags ()->getAll () );
    $this->assertSame ( $result, $filter->fetchResult ()->getResults () );
  }
  
  /**
   * @group policy
   */
  public function testPolicy () {
  
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <value pattern="/^\d+$/">
            <one>
              <value pattern="/[02468]$/" flag="odd"  />
              <value pattern="/[13579]$/" flag="even" />
            </one>
          </value>
        </pattern>
    ' );
    
    $filter = new MapFilter ( $pattern );
    
    $odd = $filter->setQuery ( '32' )->fetchResult();
    $even = $filter->setQuery ( '21' )->fetchResult();
    $nan = $filter->setQuery ( 'NaN' )->fetchResult();
    
    $this->assertSame ( Array (), $nan->getFlags ()->getAll () );
    $this->assertSame ( null, $nan->getResults () );
    
    $this->assertSame ( Array ( 'odd' ), $odd->getFlags ()->getAll () );
    $this->assertSame ( '32', $odd->getResults () );
    
    $this->assertSame ( Array ( 'even' ), $even->getFlags ()->getAll () );
    $this->assertSame ( '21', $even->getResults () );
  }
  
  /**
   *
   */
  public function testEmpty () {
  
    $a = MapFilter_TreePattern::load ( '
        <pattern><value pattern="asdf" /></pattern>
    ' );
    
    $b = MapFilter_TreePattern::load ( '
        <pattern><value pattern="asdf"></value></pattern>
    ' );
    
    $this->assertEquals ( $a, $b );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage The 'value' node must have exactly one follower but 2 given.
   */
  public function testNotExactlyOneFollower () {
  
    MapFilter_TreePattern::load ( '
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
