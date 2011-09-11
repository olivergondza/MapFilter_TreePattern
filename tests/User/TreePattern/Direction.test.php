<?php
/**
 * Direction
 */

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	User
 * @group	User::TreePattern
 * @group	User::TreePattern::Direction
 */
class MapFilter_Test_User_TreePattern_Direction extends
    PHPUnit_Framework_TestCase
{
  
  /**@{*/
  public function provideDirection () {
  
    return Array (
        // Single direction
        Array (
            Array (
                'top' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
            Array (
                'top' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
        ),
        // Two compatible directions
        Array (
            Array (
                'top'  => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'top'  => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        // Two incompatible directions; one will be trimmed
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
        ),
        // Three directions; one will be trimmed
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
        ),
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        // Four directions; two will be trimmed
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
    );
  }
  /**@}*/
  
  /**
   * @dataProvider      provideDirection
   */
  public function testDirection ( $query, $result ) {
  
    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DIRECTION
    );

    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;
    
    $this->assertEquals ( $result, $actual );
  }
  
  /**
   * @dataProvider      provideDirection
   */
  public function testNewDirection ( $query, $result ) {
  
    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DIRECTION_NEW
    );

    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;

    $this->assertEquals ( $result, $actual );
  }
}
