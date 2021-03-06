<?php
/**
 * User Tests using filter.xml
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_TestUserFilter extends MapFilter_TreePattern_Test_Functional {

  public function provideParseFilterUtility () {

    return Array (
        /** [provideParseFilterUtility] */
        Array (
            Array (),
            null
        ),
        // Valid input queries
        Array (
            Array ( '-h' => NULL ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-v' => NULL ),
            Array ( '-v' => NULL )
        ),

        Array (
            Array ( '-a' => NULL ),
            Array ( '-a' => NULL )
        ),
        Array (
            Array ( '-c' => NULL ),
            Array ( '-c' => NULL )
        ),
        Array (
            Array ( '-l' => NULL ),
            Array ( '-l' => NULL )
        ),
        Array (
            Array ( '-c' => NULL, '-l' => NULL ),
            Array ( '-c' => NULL, '-l' => NULL )
        ),
        // Truncate invalid option
        Array (
            Array ( '-m' => NULL ),
            null
        ),
        // Truncate redundant argument
        Array (
            Array ( '-h' => NULL, '-v' => NULL ),
            Array ( '-h' => NULL )
        ),
        // -a is truncated since -c and -l is defined earlier
        Array (
            Array ( '-c' => NULL, '-l' => NULL, '-a' => NULL ),
            Array ( '-c' => NULL, '-l' => NULL )
        ),
        // -c and -l are truncated since -h is defined earlier
        Array (
            Array ( '-c' => NULL, '-l' => NULL, '-h' => NULL ),
            Array ( '-h' => NULL )
        ),
        // -a is truncated since -v is defined earlier
        Array (
            Array ( '-a' => NULL, '-v' => NULL ),
            Array ( '-v' => NULL )
        ),
        /** [provideParseFilterUtility] */
    );
  }

  /**
   * @dataProvider      provideParseFilterUtility
   */
  public function testFilterUtility ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::FILTER
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }

  /**
   * @dataProvider      provideParseFilterUtility
   */
  public function testNewFilterUtility ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::FILTER_NEW
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
}
