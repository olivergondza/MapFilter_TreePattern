<?php
/**
 * Test Xml deserializer
 */

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @covers      MapFilter_TreePattern_Xml
 */
class MapFilter_TreePattern_Unit_Xml extends PHPUnit_Framework_TestCase {

  /**
   * Test whether MapFilter_TreePattern_Xml::load returns
   * MapFilter_PatternInterface
   *
   * @covers    MapFilter_TreePattern_Xml::load
   */
  public function testPatternLoadInterface () {
  
    $pattern = MapFilter_TreePattern_Xml::load ( '<attr>attr</attr>' );
    $this->assertInstanceOf ( 'MapFilter_PatternInterface', $pattern );
  }
  
  /**
   * Test whether MapFilter_TreePattern_Xml::fromFile returns
   * MapFilter_PatternInterface
   *
   * @covers    MapFilter_TreePattern_Xml::fromFile
   */
  public function testPatternFromFileInterface () {
  
    $file = PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::CAT;
  
    $pattern = MapFilter_TreePattern_Xml::fromFile ( $file );
    $this->assertInstanceOf ( 'MapFilter_PatternInterface', $pattern );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_InvalidPatternElementException
   * @expectedExceptionMessage  Invalid pattern element 'lantern'.
   *
   * @covers MapFilter_TreePattern_Xml::_unwrap
   * @covers MapFilter_TreePattern_Xml::_validateTagName
   * @covers MapFilter_TreePattern_InvalidPatternElementException
   */
  public function testInvalidRootElement () {

    MapFilter_TreePattern_Xml::load ( '<lantern></lantern>' );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_InvalidPatternElementException
   * @expectedExceptionMessage  Invalid pattern element 'wrongnode'.
   *
   * @covers MapFilter_TreePattern_Xml::_unwrap
   * @covers MapFilter_TreePattern_Xml::_validateTagName
   * @covers MapFilter_TreePattern_InvalidPatternElementException
   */
  public function testInvalidPatternElement () {
  
    MapFilter_TreePattern_Xml::load (
        '<pattern><wrongnode></wrongnode></pattern>'
    );
  }
  
  /**
   * Parse a tag that hes not been wrapped in <pattern> tags
   *
   * @covers    MapFilter_TreePattern_Xml::_unwrap
   * @covers    MapFilter_TreePattern_Xml::_unwrapPattern
   * @covers    MapFilter_TreePattern_Xml::_unwrapMultiplePatterns
   */
  public function testUnwrapped () {

    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<pattern><attr>anAttribute</attr></pattern>';
    $deepPattern = '<patterns><pattern><attr>anAttribute</attr></pattern></patterns>';
  
    $this->assertEquals (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        MapFilter_TreePattern_Xml::load ( $lazyPattern )
    );
    
    $this->assertEquals (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        MapFilter_TreePattern_Xml::load ( $deepPattern )
    );
  }
  
  public function provideColidingPatternNames () {
  
    return Array (
        Array ( '
            <patterns>
              <pattern name="main"><all /></pattern>
              <pattern name="main"><opt /></pattern>
            </patterns>'
        ),
        Array ( '
            <patterns>
              <pattern><all /></pattern>
              <pattern><opt /></pattern>
            </patterns>'
        ),
        Array ( '
            <patterns>
              <pattern name="main"><all /></pattern>
              <pattern><opt /></pattern>
            </patterns>'
        ),
        Array ( '
            <patterns>
              <pattern><all /></pattern>
              <pattern name="main"><opt /></pattern>
            </patterns>'
        ),
        Array ( '
            <patterns>
              <pattern name="has_one"><one /></pattern>
              <pattern><all /></pattern>
              <pattern name="different_name"><one /></pattern>
              <pattern name="main"><opt /></pattern>
              <pattern name="another_name"></pattern>
            </patterns>'
        ),
    );
  }
  
  /**
   * @dataProvider provideColidingPatternNames
   *
   * @expectedException MapFilter_TreePattern_ColidingPatternNamesException
   *
   * @covers    MapFilter_TreePattern_ColidingPatternNamesException
   */
  public function testColidingPatternNames ( $pattern ) {

    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  public function provideNoPatternSpecified () {
  
    return Array (
        Array ( '<patterns/>' ),
        Array ( '<patterns></patterns>' ),
    );
  }
  
  /**
   * @dataProvider provideNoPatternSpecified
   *
   * @expectedException MapFilter_TreePattern_NoPatternSpecifiedException
   * @expectedExceptionMessage No pattern specified.
   *
   * @covers    MapFilter_TreePattern_NoPatternSpecifiedException
   */  
  public function testNoPatternSpecified ( $pattern ) {
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  public function provideNoMainPattern () {
  
    return Array (
        Array ( '
            <pattern name="no_main">
              <all />
            </pattern>
        ' ),
        Array ( '
            <patterns>
              <pattern name="no_main">
                <all />
              </pattern>
            </patterns>
        ' ),
        Array ( '
            <patterns>
              <pattern name="no_main">
                <all />
              </pattern>
              <pattern name="still_no_main">
                <all />
              </pattern>
            </patterns>
        ' ),
    );
  }
  
  /**
   * @dataProvider provideNoMainPattern
   *
   * @expectedException MapFilter_TreePattern_NoMainPatternException
   * @expectedExceptionMessage No main pattern specified.
   *
   * @covers    MapFilter_TreePattern_NoMainPatternException
   * @covers    MapFilter_TreePattern_Xml
   */
  public function testNoMainPattern ( $pattern ) {
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  public function provideInvalidXml () {
  
    return Array (
        Array ( '<' ),
        Array ( '>' ),
        Array ( '<pattern' ),
        Array ( 'pattern>' ),
        Array ( '<pattern>' ),
        Array ( '</pattern>' ),
        Array ( 'pattern' ),
        Array ( '<all><opt></all></opt>' ),
        Array ( '<pattern name>' ),
        Array ( '<pattern name=>' ),
        Array ( '<pattern name=">' ),
    );
  }
  
  /**
   * @dataProvider provideInvalidXml
   *
   * @expectedException MapFilter_TreePattern_Xml_LibXmlFatalException
   *
   * @covers    MapFilter_TreePattern_Xml_LibXmlFatalException<extended>
   * @covers    MapFilter_TreePattern_Xml::_loadXml
   */
  public function testInvalidXml ( $pattern ) {
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Xml_LibXmlException
   *
   * @covers    MapFilter_TreePattern_Xml::_loadXml
   * @covers    MapFilter_TreePattern_Xml_LibXmlException
   */
  public function testWrongFile () {
  
    MapFilter_TreePattern_Xml::fromFile ( 'no_such_file.xml' );
  }
}
