<?php
/**
 * User Tests using generator.xml
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Generator extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideGenerator () {

    return Array (
        /** [provideGenerator] */
        // No source specified
        Array (
            Array (),
            null,
            Array ( 'no_source' )
        ),
        // Default 'output_type' and its 'stylesheet' assigned
        Array (
            Array ( 'input' => Array ( 'a.out' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'default.css' ) ),
            Array ()
        ),
        // Valid set
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'a.css', 'b.css' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'a.css', 'b.css' ) ),
            Array ()
        ),
        // Default 'stylesheet' for the 'html' and the default 'extension' for 'man' assigned.
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html', 'man' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html', 'man' ), 'stylesheet' => Array ( 'default.css' ), 'extension' => '3' ),
            Array ()
        ),
        // Set the default value if no 'output_type' is valid
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'pdf', 'text' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html', 'html' ), 'stylesheet' => Array ( 'default.css' ) ),
            Array ()
        ),
        // Set the default value if no 'stylesheet' is valid
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'a.sss', 'b.sss' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'default.css', 'default.css' ) ),
            Array ()
        ),
        // Replace invalid 'stylesheet' by default value
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'a.sss' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'html' ), 'stylesheet' => Array ( 'default.css' ) ),
            Array ()
        ),
        // Assign default extension
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'man' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'man' ), 'extension' => '3' ),
            Array ()
        ),
        // Valid set
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'man' ), 'extension' => '2' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'man' ), 'extension' => '2' ),
            Array ()
        ),
        // Replace invalid 'extension' value by default value.
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'man' ), 'extension' => 'info' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'man' ), 'extension' => '3' ),
            Array ()
        ),
        // Assign default values for those attributes that were not stated.
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ) ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'default.css' ), 'dtd' => 'default.dtd' ),
            Array ()
        ),
        // AssInge default values for those attributes that were not stated.
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'dtd' => 'a.dtd' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'default.css' ), 'dtd' => 'a.dtd' ),
            Array ()
        ),
        // Replace invalid 'dtd' by default value
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'dtd' => 'a.ddd' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'default.css' ), 'dtd' => 'default.dtd' ),
            Array ()
        ),
        // Truncate disallowed attribute 'extension' and set default values for chosen output type
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'extension' => '1' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'default.css' ), 'dtd' => 'default.dtd' ),
            Array ()
        ),
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'extension' => '1', 'dtd' => 'a.dtd' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'default.css' ), 'dtd' => 'a.dtd' ),
            Array ()
        ),
        // Enable multiple case competence
        Array (
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'a.css' ), 'dtd' => 'a.dtd' ),
            Array ( 'input' => Array ( 'a.out' ), 'output_format' => Array ( 'xml' ), 'stylesheet' => Array ( 'a.css' ), 'dtd' => 'a.dtd' ),
            Array ()
        ),
        /** [provideGenerator] */
    );
  }

  /**
   * @dataProvider      provideGenerator
   */
  public function testGenerator ( $query, $result, $asserts ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::GENERATOR
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, Array () );
  }
}
