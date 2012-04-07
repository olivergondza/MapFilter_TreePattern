<?php
/**
* User Tests using cat.xml
*/  

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Cat extends
    MapFilter_TreePattern_Test_Functional
{

  /*@}*/
  public function provideParseCat () {
  
    return Array (
        Array (
            Array (),
            null,
            Array (),
        ),
        Array (
            Array ( 'help' => '' ),
            Array ( 'help' => '' ),
            Array ( 'show_help' ),
        ),
        Array (
            Array ( 'version' => '' ),
            Array ( 'version' => '' ),
            Array ( 'show_version' ),
        ),
        Array (
            Array ( 'no_such_option' => '' ),
            null,
            Array (),
        ),
        Array (
            Array ( 'no_such_option' => '', 'version' => '' ),
            Array ( 'version' => '' ),
            Array ( 'show_version' )
        ),
        Array (
            Array ( 'no_such_option' => '', 'b' => '' ),
            Array ( 'b' => '' ),
            Array ( 'perform_action', 'number', 'number_nonblank' )
        ),
        Array (
            Array ( 'u' => '' ),
            null,
            Array (),
            Array ( 'deprecated' => '' ),
        ),
        Array (
            Array (
                'A' => '', 'b' => '', 'E' => '', 'n' => '',
                's' => '', 'T' => '', 'v' => ''
            ),
            Array (
                'b' => '', 'E' => '', 'n' => '',
                's' => '', 'T' => '', 'v' => ''
            ),
            Array (
                'perform_action', 'number', 'number_all', 'number_nonblank',
                'show_ends', 'show_nonprinting', 'show_tabs', 'squeeze_blank'
            )
        ),
        Array (
            Array ( 'A' => '' ),
            Array ( 'v' => '', 'E' => '', 'T' => '' ),
            Array (
                'perform_action', 'show_ends', 'show_nonprinting', 'show_tabs'
            )
        ),
        Array (
            Array ( 'show-all' => '' ),
            Array ( 'v' => '', 'E' => '', 'T' => '' ),
            Array (
                'perform_action', 'show_ends', 'show_nonprinting', 'show_tabs'
            )
        ),
        Array (
            Array ( 'e' => '' ),
            Array ( 'v' => '', 'E' => '' ),
            Array ( 'perform_action', 'show_nonprinting', 'show_ends' )
        ),
        Array (
            Array ( 't' => '' ),
            Array ( 'v' => '', 'T' => '' ),
            Array ( 'perform_action', 'show_nonprinting', 'show_tabs' )
        ),
        Array (
            Array ( 'number-nonblank' => '' ),
            Array ( 'b' => '' ),
            Array ( 'perform_action', 'number', 'number_nonblank' )
        ),
        Array (
            Array ( 'show-ends' => '' ),
            Array ( 'E' => '' ),
            Array ( 'perform_action', 'show_ends' )
        ),
        Array (
            Array ( 'number' => '' ),
            Array ( 'n' => '' ),
            Array ( 'perform_action', 'number', 'number_all' )
        ),
        Array (
            Array ( 'squeeze-blank' => '' ),
            Array ( 's' => '' ),
            Array ( 'perform_action', 'squeeze_blank' )
        ),
        Array (
            Array ( 'show-tabs' => '' ),
            Array ( 'T' => '' ),
            Array ( 'perform_action', 'show_tabs' )
        ),
        Array (
            Array ( 'show-nonprinting' => '' ),
            Array ( 'v' => '' ),
            Array ( 'perform_action', 'show_nonprinting' )
        ),
    );
  }
  /*@}*/
  
  /**
   * @dataProvider      provideParseCat
   */
  public function testCat (
      $query, $result, $flags, $asserts = Array ()
  ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::CAT
    );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
  
  /**
   * @dataProvider      provideParseCat
   */
  public function testNewCat (
      $query, $result, $flags, $asserts = Array ()
  ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::CAT_NEW
    );
    
    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
