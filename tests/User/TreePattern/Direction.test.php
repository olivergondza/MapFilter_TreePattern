<?php
/**
 * Direction
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Direction extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideDirection () {

    return Array (
        /** [provideDirection] */
        // Single direction
        Array (
            /** [TreePattern_Direction_Valid_One] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
            /** [TreePattern_Direction_Valid_One] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
        ),
        // Two compatible directions
        Array (
            /** [TreePattern_Direction_Valid_Two] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'west' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            /** [TreePattern_Direction_Valid_Two] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'west' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        // Two incompatible directions; one will be trimmed
        Array (
            /** [TreePattern_Direction_Invalid_Two] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'south' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            /** [TreePattern_Direction_Invalid_Two] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
        ),
        // Three directions; one will be trimmed
        Array (
            /** [TreePattern_Direction_Invalid_Three] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'south' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'west' => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            /** [TreePattern_Direction_Invalid_Three] */
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'west' => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
        ),
        Array (
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'east' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'west' => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'north'=> Array ( 'unit' => 'meter', 'count' => 2 ),
                'east' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        // Four directions; two will be trimmed
        Array (
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'south' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'east' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'west' => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'east' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        Array (
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'west' => Array ( 'unit' => 'meter', 'count' => 1 ),
                'south' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'east' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'north' => Array ( 'unit' => 'meter', 'count' => 2 ),
                'east' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        /** [provideDirection] */
    );
  }

  /**
   * @dataProvider      provideDirection
   */
  public function testDirection ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DIRECTION
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }

  /**
   * @dataProvider      provideDirection
   */
  public function testNewDirection ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DIRECTION_NEW
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
}
