<?php
/**
* User Tests using duration.xml
*/  

require_once PHP_TREEPATTERN_CLASS;

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Duration
*/
class MapFilter_Test_User_TreePattern_Duration extends
    PHPUnit_Framework_TestCase
{

    /**@{*/
    public function provideDuration () {
  
    return Array (
        // An absence of query set related assertions
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_hour' )
        ),
        // An absence of termination time set related assertions
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0 ),
            Array (),
            Array (),
            Array ( 'no_duration_hour', 'no_end_hour', 'no_termination_time' )
        ),
        // An invalid value sets an assertion
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 'now' ),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_second' => Array ( 
                MapFilter_TreePattern_Asserts::VALUE => 'now'
            ) )
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
            Array (),
            Array (),
            Array ('no_beginning_time', 'no_start_hour' => Array ( 
                MapFilter_TreePattern_Asserts::VALUE => -1
            ) )
        ),
        // Invalid value for set_minute set an assertion
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 60 ),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_minute' => Array ( 
                MapFilter_TreePattern_Asserts::VALUE => 60
            ) )
        )
    );
  }
  /**@}*/
  
  /**@{*/
  /**
   * @dataProvider      provideDuration
   */
  public function testDuration ( $query, $result, $flags, $asserts ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        $query
    );
    
    $this->assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Flags ( $flags ),
        $filter->fetchResult ()->getFlags ()
    );
    
    $this->assertEquals (
        new MapFilter_TreePattern_Asserts ( $asserts ),
        $filter->fetchResult ()->getAsserts ()
    );
  }
  /**@}*/
  
  /**{@*/
  /**
    * @dataProvider     provideDuration
    */
  public function testDurationArrayAccess (
      $query, $result, $flags, $asserts
  ) {
  
    $filterObject = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        new ArrayObject ( $query )
    );
    
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_TREEPATTERN_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        $query
    );
    
    $this->assertEquals (
        $filterObject->fetchResult ()->getResults (),
        $filter->fetchResult ()->getResults ()
    );
    
    $this->assertEquals (
        $filterObject->fetchResult ()->getFlags (),
        $filter->fetchResult ()->getFlags ()
    );
    
    $this->assertEquals (
        $filterObject->fetchResult ()->getAsserts (),
        $filter->fetchResult ()->getAsserts ()
    );
  }
  /**@}*/
}
