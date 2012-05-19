<?php
/**
 * User Tests using location.xml
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Location extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideLocation () {

    return Array (
        /** [provideLocation] */
        Array (
            Array (),
            null
        ),
        // Valid set
        Array (
            Array ( 'action' => 'delete', 'nick' => 'myLocation' ),
            Array ( 'action' => 'delete', 'nick' => 'myLocation' )
        ),
        // Truncate coordinates that are redundant
        Array (
            Array ( 'action' => 'delete', 'nick' => 'myLocation', 'x' => 1, 'y' => 1, 'z' => 2 ),
            Array ( 'action' => 'delete', 'nick' => 'myLocation')
        ),
        Array (
            Array ( 'action' => 'delete', 'x' => 1, 'y' => 1, 'z' => 2 ),
            Array ( 'action' => 'delete', 'x' => 1, 'y' => 1, 'z' => 2 )
        ),
        // Redundant coordinate will be truncated
        Array (
            Array ( 'action' => 'delete', 'x' => 1, 'y' => 1, 'z' => 2, 'a' => 0 ),
            Array ( 'action' => 'delete', 'x' => 1, 'y' => 1, 'z' => 2 )
        ),
        // Missing 'z' coordinate => remaining coordinates will be trimmed => action will be truncated
        Array (
            Array ( 'action' => 'delete', 'x' => 1, 'y' => 1 ),
            null
        ),
        // Action without coordinates
        Array (
            Array ( 'action' => 'delete' ),
            null
        ),
        // Redundant attribute will be truncated
        Array (
            Array ( 'action' => 'delete', 'nick' => 'myLocation', 'duration' => 'permanent' ),
            Array ( 'action' => 'delete', 'nick' => 'myLocation' )
        ),
        /** [provideLocation] */
    );
  }

  /**
   * @dataProvider      provideLocation
   */
  public function testLocation ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::LOCATION
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }

  /**
   * @dataProvider      provideLocation
   */
  public function testNewLocation ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::LOCATION_NEW
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
}
