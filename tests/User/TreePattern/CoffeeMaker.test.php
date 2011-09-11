<?php
/**
* User Tests using coffee_Maker.xml
*/  

require_once 'PHP/MapFilter/TreePattern.php';

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::CoffeeMaker
*/
class MapFilter_Test_User_TreePattern_CoffeeMaker extends PHPUnit_Framework_TestCase {

  /*@}*/
  public function provideCoffeeMaker () {
  
    return Array (
        Array (
            Array (),
            null
        ),
        // default values will be assigned
        Array (
            Array ( 'beverage' => 'coffee' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        // invalid value will be replaced by default
        Array (
            Array ( 'beverage' => 'tea', 'cup' => 2 ),
            Array ( 'beverage' => 'tea', 'cup' => 'yes', 'sugar' => 0 )
        ),
        // invalid value will be replaced by default
        Array (
            Array ( 'beverage' => 'cacao', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'cacao', 'cup' => 'yes', 'sugar' => 0 )
        ),
        // invalid values will be replaced by defaults
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'none', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 ),
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 )
        ),
        // disallowed attribute will be truncated
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5, 'spoon' => 'please' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 )
        )
    );
  }
  /*@}*/
  
  /**
   * @dataProvider      provideCoffeeMaker
   */
  public function testCoffeeMaker ( $query, $expected ) {
  
    $filename =
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::COFFEE_MAKER
    ;
  
    $actual = MapFilter_TreePattern_Xml::fromFile ( $filename )
        ->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;
    
    $this->assertEquals ( $expected, $actual );
  }
  
  /**
   * @dataProvider      provideCoffeeMaker
   */
  public function testNewCoffeeMaker ( $query, $expected ) {

    $filename =
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::COFFEE_MAKER_NEW
    ;
  
    $actual = MapFilter_TreePattern_Xml::fromFile ( $filename )
        ->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;
    
    $this->assertEquals ( $expected, $actual );
  }
}
