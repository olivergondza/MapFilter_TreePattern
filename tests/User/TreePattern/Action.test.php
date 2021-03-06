<?php
/**
 * User Tests using action.xml
 */

require_once 'PHP/MapFilter/TreePattern.php';
require_once 'tests/Functional.php';

/**
 *
 */
class MapFilter_Test_User_TreePattern_Action extends
    MapFilter_TreePattern_Test_Functional
{

  public function provideParseAction () {

    return Array (
        /** [provideParseAction] */
        Array (
            Array (),
            null
        ),
        // invalid action will be truncated
        Array (
            Array ( 'action' => 'noSuchAction' ),
            null
        ),
        Array (
            Array ( 'action' => 'delete', 'id' => 42 ),
            Array ( 'action' => 'delete', 'id' => 42 )
        ),
        Array (
            Array ( 'action' => 'delete', 'file_name' => 'myFile' ),
            Array ( 'action' => 'delete', 'file_name' => 'myFile' )
        ),
        // action without mandatory attribute (id or delete) will be truncated
        Array (
            Array ( 'action' => 'delete', 'wrongAttr' => NULL ),
            null
        ),
        // earlier defined value will be kept the rest will be truncated
        Array (
            Array ( 'action' => 'delete', 'id' => 42, 'file_name' => 'myFile' ),
            Array ( 'action' => 'delete', 'id' => 42 )
        ),
        Array (
            Array ( 'action' => 'create', 'new_file' => 'fileObj', 'new_name' => 'myFile' ),
            Array ( 'action' => 'create', 'new_file' => 'fileObj', 'new_name' => 'myFile' )
        ),
        Array (
            Array ( 'action' => 'create', 'new_file' => 'fileObj' ),
            Array ( 'action' => 'create', 'new_file' => 'fileObj' )
        ),
        Array (
            Array ( 'action' => 'rename', 'id' => 42, 'new_name' => 'myFile' ),
            Array ( 'action' => 'rename', 'id' => 42, 'new_name' => 'myFile' )
        ),
        Array (
            Array ( 'action' => 'rename', 'old_name' => 'myFile', 'new_name' => 'myFile' ),
            Array ( 'action' => 'rename', 'old_name' => 'myFile', 'new_name' => 'myFile' )
        ),
        // action that lacks mandatory attribute (id and old_name) will be truncated
        Array (
            Array ( 'action' => 'rename', 'new_name' => 'myFile' ),
            null
        ),
        // action that lacks mandatory attribute (new_name) will be truncated
        Array (
            Array ( 'action' => 'rename', 'old_name' => 'myFile' ),
            null
        ),
        // action that lacks mandatory attribute will be truncated
        Array (
            Array ( 'action' => 'rename' ),
            null
        ),
        Array (
            Array ( 'action' => 'report', 'id' => 42 ),
            Array ( 'action' => 'report', 'id' => 42 )
        ),
        // action that lacks mandatory attribute (id) will be truncated
        Array (
            Array ( 'action' => 'report' ),
            null
        ),
        // action that lacks mandatory attribute (id) will be truncated
        Array (
            Array ( 'action' => 'report', 'file_name' => 'myName' ),
            null
        )
        /** [provideParseAction] */
    );
  }

  /**
   * @dataProvider      provideParseAction
   */
  public function testParseAction ( $query, $result ) {

    $pattern = MapFilter_TreePattern_Xml::fromFile (
        PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::ACTION
    );

    $this->assertResultsEquals ( $pattern, $query, $result );
  }
}
