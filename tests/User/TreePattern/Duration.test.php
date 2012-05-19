<?php
/**
 * User Tests using duration.xml
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Duration extends
    MapFilter_TreePattern_Test_Functional
{

    public function provideDuration () {

    return Array (
        /** [provideDuration] */
        // An absence of query set related assertions
        Array (
            Array (),
            null,
            Array (),
            Array ( 'no_beginning_time', 'no_start_hour' )
        ),
        // An absence of termination time set related assertions
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0 ),
            null,
            Array (),
            Array ( 'no_duration_hour', 'no_end_hour', 'no_termination_time' )
        ),
        // An invalid value sets an assertion
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 'now' ),
            null,
            Array (),
            Array ( 'no_beginning_time', 'no_start_second' => 'now' )
        ),
        // Query OK; Nothing get trimmed; Appropriate flags are set
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0, 'end_hour' => 1, 'end_minute' => 59, 'end_second' => 59 ),
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0, 'end_hour' => 1, 'end_minute' => 59, 'end_second' => 59 ),
            Array ( 'duration', 'ending_time' ),
            Array ()
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0, 'duration_hour' => 1, 'duration_minute' => 59, 'duration_second' => 59 ),
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0, 'duration_hour' => 1, 'duration_minute' => 59, 'duration_second' => 59 ),
            Array ( 'duration', 'duration_time' ),
            Array ( 'no_end_hour' )
        ),
        // Invalid value for start_hour set an assertion
        Array (
            Array ( 'start_hour' => -1 ),
            null,
            Array (),
            Array ('no_beginning_time', 'no_start_hour' => -1 )
        ),
        // Invalid value for set_minute set an assertion
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 60 ),
            null,
            Array (),
            Array ( 'no_beginning_time', 'no_start_minute' => 60 )
        )
        /** [provideDuration] */
    );
  }

  /**
   * @dataProvider      provideDuration
   */
  public function testDuration ( $query, $result, $flags, $asserts ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DURATION
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }

  /**
   * @dataProvider      provideDuration
   */
  public function testNewDuration ( $query, $result, $flags, $asserts ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DURATION_NEW
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
