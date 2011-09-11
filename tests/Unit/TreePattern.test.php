<?php
/**
 * Require tested class
 */
require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 */
class MapFilter_Test_Unit_TreePattern extends PHPUnit_Framework_TestCase {  
  
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
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionMessage MapFilter_TreePattern::load is deprecated
   */
  public function testTreePatternLoadDeprecated () {
  
    MapFilter_TreePattern::load ( null );
  }
  
  /**
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionMessage MapFilter_TreePattern::fromFile is deprecated
   */
  public function testTreePatternFromFileDeprecated () {
  
    MapFilter_TreePattern::fromFile ( null );
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
  
  
  
  public function provideWrongAttribute () {
  
    return Array (
        /** An attr attribute */
        Array (
            '<pattern><all attr="attrName" /></pattern>',
            "Node 'all' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><one attr="attrName" /></pattern>',
            "Node 'one' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><opt attr="attrName" /></pattern>',
            "Node 'opt' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><some attr="attrName" /></pattern>',
            "Node 'some' has no attribute like 'attr'."
        ),
        
        /** An valuePattern attribute */
        Array (
            '<pattern><all valuePattern="pattern" /></pattern>',
            "Node 'all' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><one valuePattern="pattern" /></pattern>',
            "Node 'one' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><opt valuePattern="pattern" /></pattern>',
            "Node 'opt' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><some valuePattern="pattern" /></pattern>',
            "Node 'some' has no attribute like 'valuePattern'."
        ),
        
        /** An valueReplacement attribute */
        Array (
            '<pattern><all valueReplacement="pattern" /></pattern>',
            "Node 'all' has no attribute like 'valueReplacement'."
        ),
        Array (
            '<pattern><one valueReplacement="pattern" /></pattern>',
            "Node 'one' has no attribute like 'valueReplacement'."
        ),
        Array (
            '<pattern><opt valueReplacement="pattern" /></pattern>',
            "Node 'opt' has no attribute like 'valueReplacement'."
        ),
        Array (
            '<pattern><some valueReplacement="pattern" /></pattern>',
            "Node 'some' has no attribute like 'valueReplacement'."
        ),
        
        /** A default attribute */
        Array (
            '<pattern><all default="defaultValue" /></pattern>',
            "Node 'all' has no attribute like 'default'."
        ),
        Array (
            '<pattern><one default="defaultValue" /></pattern>',
            "Node 'one' has no attribute like 'default'."
        ),
        Array (
            '<pattern><opt default="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'default'."
        ),
        Array (
            '<pattern><some default="defaultValue" /></pattern>',
            "Node 'some' has no attribute like 'default'."
        ),
        
        /** A validationDefault attribute */
        Array (
            '<pattern><all validationDefault="defaultValue" /></pattern>',
            "Node 'all' has no attribute like 'validationDefault'."
        ),
        Array (
            '<pattern><one validationDefault="defaultValue" /></pattern>',
            "Node 'one' has no attribute like 'validationDefault'."
        ),
        Array (
            '<pattern><opt validationDefault="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'validationDefault'."
        ),
        Array (
            '<pattern><some validationDefault="defaultValue" /></pattern>',
            "Node 'some' has no attribute like 'validationDefault'."
        ),
        
        /** A existenceDefault attribute */
        Array (
            '<pattern><all existenceDefault="defaultValue" /></pattern>',
            "Node 'all' has no attribute like 'existenceDefault'."
        ),
        Array (
            '<pattern><one existenceDefault="defaultValue" /></pattern>',
            "Node 'one' has no attribute like 'existenceDefault'."
        ),
        Array (
            '<pattern><opt existenceDefault="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'existenceDefault'."
        ),
        Array (
            '<pattern><some existenceDefault="defaultValue" /></pattern>',
            "Node 'some' has no attribute like 'existenceDefault'."
        ),
        
        /** A iterator attribute */
        Array (
            '<pattern><all iterator="yes" /></pattern>',
            "Node 'all' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><one iterator="yes" /></pattern>',
            "Node 'one' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><opt iterator="yes" /></pattern>',
            "Node 'opt' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><some iterator="yes" /></pattern>',
            "Node 'some' has no attribute like 'iterator'."
        ),
    );
  }
  
  /**
   * Get wrong attribute
   *
   * @dataProvider provideWrongAttribute
   *
   * @covers MapFilter_TreePattern_Tree_Builder
   */
  public function testWrongAttribute ( $pattern, $exception ) {

    try {

      MapFilter_TreePattern_Xml::load ( $pattern );
      $this->fail ( 'No exception risen.' );
    } catch ( MapFilter_TreePattern_InvalidPatternAttributeException $ex ) {

      $this->assertEquals ( $exception, $ex->getMessage () );
    }
  }
  
  public function provideCompareAttached () {
  
    return Array (
        Array (
            Array (),
            null
        ),
        Array (
            Array ( 'a' => 'val' ),
            Array ( 'a' => 'val' )
        ),
        Array (
            Array ( 'b' => 'val' ),
            null
        ),
        Array (
            Array ( 'a' => 'val', 'b' => 'var' ),
            Array ( 'a' => 'val' )
        )
    );
  }
  
  /**
   * @dataProvider      provideCompareAttached
   */
  public function testSimpleCompareAttached ( $query, $result ) {

    $simple = MapFilter_TreePattern_Xml::load ( '
        <pattern>
            <all>
              <attr>a</attr>
            </all>
        </pattern>
    ' );
    
    $simple = new MapFilter ( $simple, $query );
    
    $assembled = MapFilter_TreePattern_Xml::load ( '
        <patterns>
          <pattern>
            <all attachPattern="second" />
          </pattern>
          <pattern name="second">
            <attr>a</attr>
          </pattern>
        </patterns>
    ' );

    $assembled = new MapFilter ( $assembled, $query );
    
    $this->assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        $result,
        $simple->fetchResult ()->getResults ()
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
   * @covers    MapFilter_TreePattern_Xml
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
   * @covers    MapFilter_TreePattern_Xml
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
  
    $filter = MapFilter_TreePattern_Xml::fromFile ( 'no_such_file.xml' );
  }
  
  /**
   * @covers MapFilter_TreePattern::getFilter
   */
  public function testGetFilter () {
  
    $query = 42;
    $differentQuery = 43;
  
    $pattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <value />
        </pattern>
    ' );
    
    $differentPattern = MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <all />
        </pattern>
    ' );
    
    $this->assertEquals (
        new MapFilter ( $pattern, $query ),
        $pattern->getFilter ( $query )
    );
    
    $this->assertNotEquals (
        new MapFilter ( $pattern, $query ),
        $pattern->getFilter ( $differentQuery )
    );
    
    $this->assertNotEquals (
        new MapFilter ( $pattern, $query ),
        $differentPattern->getFilter ( $query )
    );
    
    $this->assertNotEquals (
        new MapFilter ( $pattern, $query ),
        $differentPattern->getFilter ( $differentQuery )
    );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_InvalidPatternNameException
   * @expectedExceptionMessage  Pattern 'NoSuchPattern' can not be attached.
   *
   * @covers    MapFilter_TreePattern_InvalidPatternNameException
   * @covers    MapFilter_TreePattern
   */
  public function testWrongPatternAttachment () {
  
    $pattern = '
        <pattern>
          <all attachPattern="NoSuchPattern" />
        </pattern>
    ';
    
    MapFilter_TreePattern_Xml::load ( $pattern )
        ->getFilter ( false )
        ->fetchResult ()
    ;
  }
}
