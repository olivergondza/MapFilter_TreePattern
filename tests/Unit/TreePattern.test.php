<?php
/**
 * Require tested class
 */

require_once 'PHP/MapFilter/TreePattern.php';

/**
 *
 */
class MapFilter_Test_Unit_TreePattern extends PHPUnit_Framework_TestCase {  
  
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

    $simple = $simple->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;
    
    $assembled = $assembled->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;
    
    $this->assertEquals ( $result, $assembled );
    $this->assertEquals ( $result, $simple );
  }
  
  /**
   * @covers MapFilter_TreePattern::getFilter
   */
  public function testGetFilterEquivalency () {
  
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
  
  public function testDistinctResultType () {
  
    $pattern = MapFilter_TreePattern_Xml::load (
        '<pattern><value /></pattern>'
    );
    
    $result = $pattern->getFilter ( null )->fetchResult ();
    
    $this->assertInstanceOf ( 'MapFilter_TreePattern', $pattern );
    $this->assertInstanceOf ( 'MapFilter_TreePattern_Result', $result );

    $this->assertFalse ( $pattern instanceof MapFilter_TreePattern_Result );
    $this->assertFalse ( $result instanceof MapFilter_TreePattern );
  }
}
