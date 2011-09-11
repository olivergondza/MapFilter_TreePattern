<?php
/**
* User Tests using cat.xml
*/  

require_once 'PHP/MapFilter/TreePattern.php';

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Cat
*/
class MapFilter_Test_User_TreePattern_Cat extends PHPUnit_Framework_TestCase {

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
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'u' => '' ),
            null,
            Array (),
            Array ( 'deprecated' => Array (
                MapFilter_TreePattern_Asserts::VALUE => ''
            ) ),
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
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'A' => '' ),
            Array ( 'v' => '', 'E' => '', 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-all' => '' ),
            Array ( 'v' => '', 'E' => '', 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'e' => '' ),
            Array ( 'v' => '', 'E' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 't' => '' ),
            Array ( 'v' => '', 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'number-nonblank' => '' ),
            Array ( 'b' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-ends' => '' ),
            Array ( 'E' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'number' => '' ),
            Array ( 'n' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'squeeze-blank' => '' ),
            Array ( 's' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-tabs' => '' ),
            Array ( 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-nonprinting' => '' ),
            Array ( 'v' => '' ),
            Array ( 'perform_action' )
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
    
    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
    ;
    
    $this->assertEquals ( $result, $actual->getResults () );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $actual->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $actual->getAsserts ()
    );
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
    
    $actual = $pattern->getFilter ( $query )
        ->fetchResult ()
    ;
    
    $this->assertEquals ( $result, $actual->getResults () );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $actual->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $actual->getAsserts ()
    );
  }
}
