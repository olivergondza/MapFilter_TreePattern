<?php
/**
 * Pathway
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Pathway extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideParse () {

    return Array (
        /** [provideParse] */
        // Just turn
        Array (
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' )
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' )
                )
            )
        ),
        // Just walk
        Array (
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    )
                )
            )
        ),
        // Walk and turn
        Array (
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),

                )
            )
        ),
        // Turn and walk
        Array (
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    )
                )
            )
        ),
        Array (
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            )
        ),
        /** [provideParse] */
    );
  }

  public function providePathway () {

    // Empty; Mandatory attribute "steps" is missing
    $empty = Array (
        Array (
            Array (),
            Array (),
        ),
        Array (
            Array ( 'steps' => Array () ),
            Array (),
        ),
    );

    return array_merge ( $this->provideParse (), $empty );
  }

  /**
   * @dataProvider      providePathway
   */
  public function testPathway ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PATHWAY
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }

  public function provideNewPathway () {

    // Empty; Mandatory attribute "steps" is missing
    $empty = Array (
        Array (
            null,
            null,
        ),
        Array (
            Array (),
            null,
        ),
        Array (
            Array ( 'steps' => Array () ),
            Array ( 'steps' => Array () ),
        ),
    );

    return array_merge ( $this->provideParse (), $empty );
  }

  /**
   * @dataProvider      provideNewPathway
   */
  public function testNewPathway ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PATHWAY_NEW
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
}
