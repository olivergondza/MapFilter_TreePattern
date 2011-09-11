<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Attr
 *
 * @covers MapFilter_TreePattern_Tree_Leaf_Attr<extended>
 * @covers MapFilter_TreePattern_Tree_Leaf_Attr_Builder<extended>
 * @covers MapFilter_TreePattern_Tree_Attribute
 */
class MapFilter_Test_Unit_TreePattern_Attr extends
    PHPUnit_Framework_TestCase
{
  
  /** Attribute tag value should overwrite attribute value */
  public function testAttrOverwrite () {
  
    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<attr attr="wrongAttribute">anAttribute</attr>';
    
    $this->assertEquals (
        MapFilter_TreePattern_Xml::load ( $lazyPattern ),
        MapFilter_TreePattern_Xml::load ( $pattern )
    );
  }
  
  /** Compare attributes sat by different ways */
  public function testCompareAttrs () {
  
    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<attr attr="anAttribute"></attr>';
    
    $this->assertEquals (
        MapFilter_TreePattern_Xml::load ( $lazyPattern ),
        MapFilter_TreePattern_Xml::load ( $pattern )
    );
  }
  
  /**
   * @group     Unit::TreePattern::Attr::testAttr
   */
  public function testAttr () {
    
    $query = Array ( 'attr0' => 'value' );

    $pattern = MapFilter_TreePattern_Xml::load (
        '<pattern><attr>attr0</attr></pattern>'
    );

    $filter = new MapFilter (
        $pattern,
        $query
    );

    $this->assertEquals (
        $query,
        $filter->fetchResult ()->getResults ()
    );
  }
    
  public function provideAssertEmptyAttr () {
  
    return Array (
        Array ( '<attr attr="" />' ),
        Array ( '<attr attr=""></attr>' ),
        Array ( '<attr></attr>' ),
        Array ( '<attr />' ),
    );
  }
  
  /**
   * Rise an exception in case of no attr value
   *
   * @dataProvider	provideAssertEmptyAttr
   *
   * @expectedException MapFilter_TreePattern_Tree_Attribute_MissingValueException
   * @expectedExceptionMessage There is an Attr node without attribute value specified.
   *
   * @covers MapFilter_TreePattern_Tree_Attribute_MissingValueException 
   * @covers MapFilter_TreePattern_Tree_Attribute
   */
  public function testAssertEmptyAttr ( $pattern ) {
  
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
  
  /**
   * Test default value for both nodes that support iterator attribute
   */
  public function testDefaultArrayValue () {
  
    $patternNoArrayValue =
        '<pattern><attr iterator="no">an_attr</attr></pattern>'
    ;
    $patternDefault = '<pattern><attr>an_attr</attr></pattern>';
    
    $this->assertEquals (
        MapFilter_TreePattern_Xml::load ( $patternNoArrayValue ),
        MapFilter_TreePattern_Xml::load ( $patternDefault )
    );
    
    $patternNoArrayValue =
        '<pattern><key_attr iterator="no" attr="an_attr"></key_attr></pattern>'
    ;
    $patternDefault = '<pattern><key_attr attr="an_attr"></key_attr></pattern>';
    
    $this->assertEquals (
        MapFilter_TreePattern_Xml::load ( $patternNoArrayValue ),
        MapFilter_TreePattern_Xml::load ( $patternDefault )
    );
  }
  
  /**
   * Use unsupported value as an iterator depth indicator
   *
   * @expectedException MapFilter_TreePattern_Tree_Leaf_InvalidDepthIndicatorException
   * @expectedExceptionMessage Unsupported value 'auto' for iterator attribute.
   *
   * @covers MapFilter_TreePattern_Tree_Leaf_InvalidDepthIndicatorException 
   * @covers MapFilter_TreePattern_Tree_Leaf_Attr_Builder<extended>
   */
  public function testInvalidIteratorValue () {
  
    MapFilter_TreePattern_Xml::load ( '
        <pattern>
          <attr iterator="auto">attr</attr>
        </pattern>
    ' );
  }
  
  public function provideAttrArrayValue () {
  
    return Array (
        Array (
            Array ( 'an_attr' => Array ( 'val1', 'val2' ) ),
            Array ( 'an_attr' => Array ( 'val1' ) ),
            Array ( 'wrong_attr' => Array (
                MapFilter_TreePattern_Asserts::VALUE => Array ( 'val2' )
            ) ),
            Array ( 'an_attr' )
        ),
        Array (
            Array ( 'an_attr' => Array ( 'val1', 'val1' ) ),
            Array ( 'an_attr' => Array ( 'val1', 'val1' ) ),
            Array (),
            Array ( 'an_attr' )
        ),
        Array (
            Array ( 'an_attr' => Array ( 'val2', 'val2' ) ),
            null,
            Array ( 'wrong_attr' => Array (
                MapFilter_TreePattern_Asserts::VALUE => Array ( 'val2', 'val2' )
            ) ),
            Array ()
        ),
        Array (
            Array ( 'an_attr' => Array () ),
            null,
            Array ( 'wrong_attr' => 'wrong_attr' ),
            Array (),
        ),
        Array (
            Array (),
            null,
            Array ( 'wrong_attr' => 'wrong_attr' ),
            Array ()
        ),
    );
  }
  
  /**
   * Test array filtering
   *
   * @dataProvider      provideAttrArrayValue
   * @group             Unit::TreePattern::Attr::testAttrArrayValue
   */
  public function testAttrArrayValue (
      $query, $results, $asserts, $flags
  ) {
  
    $pattern = '<pattern>
        <attr
            iterator="yes"
            valuePattern="val1"
            assert="wrong_attr"
            flag="an_attr"
        >an_attr</attr>
    </pattern>';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $results,
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $filter->fetchResult ()->getAsserts ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $filter->fetchResult ()->getFlags ()
    );
  }
  
  public function provideValidationAndExistenceDefaults () {
  
    return Array (
        Array (
            /* Keep valid */
            Array ( 'attr' => 'Valid value' ),
            Array ( 'attr' => 'Valid value' )
        ),
        Array (
            /* Set existence default */
            Array (),
            Array ( 'attr' => 'New value' )
        ),
        Array (
            /* Substitute validation default */
            Array ( 'attr' => '6' ),
            Array ( 'attr' => 'Better value' )
        )
    );
  }
  
  /**
   * @dataProvider      provideValidationAndExistenceDefaults
   */
  public function testValidationAndExistenceDefaults (
      $query, $result
  ) {
  
    $pattern = '
    <pattern>
      <attr
          existenceDefault="New value"
          validationDefault="Better value"
          valuePattern="[^0-9]*"
      >attr</attr>
    </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  public function provideValidationAndExistenceDefaultsOnArray () {
  
    return Array (
        Array (
            Array (),
            Array ( 'attr' => Array ( 'New value' ) )
        ),
        Array (
            Array ( 'attr' => Array () ),
            Array ( 'attr' => Array ( 'Better value' ) ),
        ),
        Array (
            Array ( 'attr' => Array ( '0' ) ),
            Array ( 'attr' => Array ( 'Better value' ) ),
        )
    );
  }
  
  /**
   * @dataProvider      provideValidationAndExistenceDefaultsOnArray
   */
  public function testValidationAndExistenceDefaultsOnArray (
      $query, $result
  ) {
  
    $pattern = '
    <pattern>
      <attr
          existenceDefault="New value"
          validationDefault="Better value"
          valuePattern="[^0-9]*"
          iterator="1"
      >attr</attr>
    </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  public function provideLargeDepthIterator () {
  
    return Array (
        Array (
            // default value assigned
            Array (),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // valid value kept
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ) ) )
        ),
        Array (
            // invalid value replaced by default
            Array ( 'weight' => Array ( Array ( Array ( 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // multiple values
            Array ( 'weight' => Array ( Array ( Array ( 1, 2 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1, 2 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 2 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 2 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 2 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 2 ) ) ) )
        ),
        Array (
            // multiple values with invalid pieces
            Array ( 'weight' => Array ( Array ( Array ( 1, 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1, 0 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 0 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // too flat
            Array ( 'weight' => 5 ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // still too flat
            Array ( 'weight' => Array ( 5 ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // still too flat
            Array ( 'weight' => Array ( Array ( 5 ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // too deep
            Array ( 'weight' => Array ( Array ( Array ( Array ( 1 ) ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // incavated cube with heawy corners
            Array ( 'weight' => Array (
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) ),
                Array ( Array ( 1, 1, 1 ), Array ( 1, 0, 1 ), Array ( 1, 1, 1 ) ),
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) )
            ) ),
            Array ( 'weight' => Array (
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) ),
                Array ( Array ( 1, 1, 1 ), Array ( 1, 0, 1 ), Array ( 1, 1, 1 ) ),
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) )
            ) )
        )
        
    );
  }
  
  /**
   * @dataProvider      provideLargeDepthIterator
   */
  public function testLargeDepthIterator (
      Array $query, Array $result
  ) {
  
    $pattern = '
        <pattern>
          <attr
              valuePattern="[0-9][0-9]*"
              default="0"
              iterator="3"
          >weight</attr>
        </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  /**
   *
   */
  public function testSpecialChars () {
  
    $pattern = '
        <pattern>
          <attr valuePattern="as/df/gh">name</attr>
        </pattern>
    ';
    
    $result = $query = Array ( 'name' => 'as/df/gh' );
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  public function provideValueReplacement () {
  
    return Array (
        Array (
            Array ( 'name' => '' ),
            null
        ),
        Array (
            Array ( 'name' => 'away' ),
            null
        ),
        Array (
            Array ( 'name' => '0' ),
            Array ( 'name' => '0' )
        ),
        Array (
            Array ( 'name' => 'about 15' ),
            Array ( 'name' => '15' )
        ),
        Array (
            Array ( 'name' => '15 km' ),
            Array ( 'name' => '15' )
        ),
        Array (
            Array ( 'name' => 'just 15 km' ),
            Array ( 'name' => '15' )
        ),
        Array (
            Array ( 'name' => 'just 15 km and 300 metres' ),
            Array ( 'name' => '15' )
        ),
    );
  }
  
  /**
   * @dataProvider      provideValueReplacement
   * @group	        Unit::TreePattern::Attr::testValueReplacement
   */
  public function testValueReplacement ( $query, $result ) {
  
    $pattern = '
        <pattern>
          <attr
              valuePattern="/\d+/"
              valueReplacement="/^\D*(\d+).*$/$1/"
          >name</attr>
        </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  public function provideValidationExistenceAssert () {
  
    return Array (
        Array (
            Array ( 'attr' => '0' ),
            Array ( 'attr' => '0' ),
            Array ()
        ),
        Array (
            Array (),
            null,
            Array ( 'nExist' => 'nExist' )
        ),
        Array (
            Array ( 'attr' => 'asdf' ),
            null,
            Array ( 'nValid' => Array (
                MapFilter_TreePattern_Asserts::VALUE => 'asdf'
            ) )
        ),
    );
  }
  
  /**
   * @dataProvider      provideValidationExistenceAssert
   */
  public function testValidationExistenceAssert (
      $query, $result, $asserts
  ) {
  
    $pattern = '
        <pattern>
          <attr
              existenceAssert="nExist"
              validationAssert="nValid"
              valuePattern="/\d+/"
          >attr</attr>
        </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern_Xml::load ( $pattern ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $filter->fetchResult ()->getAsserts ()
    );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_InvalidContentException
   * @expectedExceptionMessage Node 'attr' has no content
   *
   * @covers MapFilter_TreePattern_Tree_InvalidContentException
   */
  public function testContent () {
  
    $pattern = '
        <attr attr="anAttr">
          <attr attr="anotherAttr" />
        </attr>
    ';
    
    MapFilter_TreePattern_Xml::load ( $pattern );
  }
}
