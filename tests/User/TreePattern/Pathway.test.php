<?php
/**
 * Pathway
 */

require_once 'PHP/MapFilter/TreePattern.php';

/**
 * @group	User
 * @group	User::TreePattern
 * @group	User::TreePattern::Pathway
 */
class MapFilter_Test_User_TreePattern_Pathway extends
    PHPUnit_Framework_TestCase
{
  
  public function provide () {
  
    return Array (
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

    return array_merge ( $this->provide (), $empty );
  }
  
  /**
   * @dataProvider      providePathway
   */
  public function testPathway ( $query, $result ) {

    $pattern = MapFilter_TreePattern::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PATHWAY
    );

    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;
    
    $this->assertEquals ( $result, $actual );
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
    
    return array_merge ( $this->provide (), $empty );
  }
  
  /**
   * @dataProvider      provideNewPathway
   */
  public function testNewPathway ( $query, $result ) {

    $pattern = MapFilter_TreePattern::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::PATHWAY_NEW
    );

    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
        ->getResults ()
    ;

    $this->assertEquals ( $result, $actual );
  }
}
