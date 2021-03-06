<?php
/**
 * User Tests using login.xml
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Login extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideLogin () {

    return Array (
        /** [provideLogin] */
        // Valid set; 'login' flag will be set
        Array (
            Array ( 'name' => 'me', 'pass' => 'myPass' ),
            Array ( 'name' => 'me', 'pass' => 'myPass' ),
            Array ( 'login' ),
            Array ()
        ),
        // Valid set; 'login' and 'use_https' flags will be set
        Array (
            Array ( 'name' => 'me', 'pass' => 'myPass', 'use-https' => 'yes' ),
            Array ( 'name' => 'me', 'pass' => 'myPass', 'use-https' => 'yes' ),
            Array ( 'login', 'use_https' ),
            Array ()
        ),
        // Valid set; 'login' and 'remember' flags will be set
        Array (
            Array ( 'name' => 'me', 'pass' => 'myPass', 'remember' => 'yes', 'server' => NULL ),
            Array ( 'name' => 'me', 'pass' => 'myPass', 'remember' => 'yes', 'server' => NULL ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        // 'use-https' has got an invalid value
        Array (
            Array ( 'name' => 'me', 'pass' => 'myPass', 'use-https' => 'no', 'remember' => 'yes', 'server' => NULL ),
            Array ( 'name' => 'me', 'pass' => 'myPass', 'remember' => 'yes', 'server' => NULL ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        // 'use-https' has got an invalid value and one of 'user' and 'server' attributes is redundant
        Array (
            Array ( 'name' => 'me', 'pass' => 'myPass', 'use-https' => 'no', 'remember' => 'yes', 'server' => NULL, 'user' => NULL ),
            Array ( 'name' => 'me', 'pass' => 'myPass', 'remember' => 'yes', 'user' => NULL ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        // Since 'name' is a mandatory attributes the following set is invalid
        Array (
            Array (),
            null,
            Array (),
            Array ( 'no_name' )
        ),
        // Since 'password' is a mandatory attributes the following set is invalid
        Array (
            Array ( 'name' => 'me' ),
            null,
            Array (),
            Array ( 'no_password' )
        ),
        // None of 'user' and 'server' attributes was found;
        Array (
            Array ( 'name' => 'me', 'pass' => 'myPass', 'use-https' => 'yes', 'remember' => 'yes' ),
            Array ( 'name' => 'me', 'pass' => 'myPass', 'use-https' => 'yes' ),
            Array ( 'login', 'use_https' ),
            Array ( 'no_remember_method' )
        )
        /** [provideLogin] */
    );
  }

  /**
   * @dataProvider      provideLogin
   */
  public function testLogin ( $query, $result, $flags, $asserts ) {

    sort ( $flags );

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::LOGIN
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }

  /**
   * @dataProvider      provideLogin
   */
  public function testNewLogin ( $query, $result, $flags, $asserts ) {

    sort ( $flags );

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::LOGIN_NEW
    );

    $this->assertResultsEquals ( $pattern, $query, $result, $asserts, $flags );
  }
}
