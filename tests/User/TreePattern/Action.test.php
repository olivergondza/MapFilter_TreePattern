<?php
/**
* User Tests using action.xml
*/

require_once PHP_TREEPATTERN_CLASS;

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Action
*/
class MapFilter_Test_User_TreePattern_Action extends
    PHPUnit_Framework_TestCase
{

  /*@{*/
  public function provideParseAction () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        // invalid action will be truncated
        Array (
            Array ( 'action' => 'noSuchAction' ),
            Array ()
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
            Array ()
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
            Array ()
        ),
        // action that lacks mandatory attribute (new_name) will be truncated
        Array (
            Array ( 'action' => 'rename', 'old_name' => 'myFile' ),
            Array ()
        ),
        // action that lacks mandatory attribute will be truncated
        Array (
            Array ( 'action' => 'rename' ),
            Array ()
        ),
        Array (
            Array ( 'action' => 'report', 'id' => 42 ),
            Array ( 'action' => 'report', 'id' => 42 )
        ),
        // action that lacks mandatory attribute (id) will be truncated
        Array (
            Array ( 'action' => 'report' ),
            Array ()
        ),
        // action that lacks mandatory attribute (id) will be truncated
        Array (
            Array ( 'action' => 'report', 'file_name' => 'myName' ),
            Array ()
        )
    );
  }
  /*@}*/
  
  /**
   * Test parse external source and validate
   * @dataProvider      provideParseAction
   */
  public function testParseAction ( $query, $result ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::ACTION
        ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults()
    );
  }
}
