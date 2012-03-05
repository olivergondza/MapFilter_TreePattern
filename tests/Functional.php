<?php

require_once 'PHP/MapFilter/TreePattern.php';

/**
 *
 */
abstract class MapFilter_TreePattern_Test_Functional extends
    PHPUnit_Framework_TestCase
{

  /**
   * Assert all result types equals expected values
   *
   * @param     MapFilter_PatternInterface      $pattern
   * @param     mixed                           $query
   * @param     mixed                           $result
   * @param     mixed                           $asserts
   * @param     mixed                           $flags
   */
  protected function assertResultsEquals (
      MapFilter_PatternInterface $pattern,
      $query,
      $result,
      $asserts = Array (),
      $flags = Array ()
  ) {
  
    sort ( $flags );
  
    $actual = $pattern->getFilter ( $query )->fetchResult ();
    $this->_compare ( $actual, $result, $asserts, $flags );
    
    if ( !is_array ( $query ) ) return;
    
    $actual = $pattern->getFilter ( $this->_getIterator ( $query ) )
        ->fetchResult ()
    ;

    $this->_compareIterator ( $actual, $result, $asserts, $flags );
  }
  
  /**
   * Wrap array to iterator
   *
   * @param     Array           $query
   * @return    Iterator        Wrapped query
   */
  private function _getIterator ( Array $query ) {
  
    if ( empty ( $query ) ) return new ArrayObject ( new EmptyIterator );
    
    return new ArrayIterator ( $query );
  }
  
  /**
   * 
   */
  private function _compare ( $actual, $result, $asserts, $flags ) {

    $this->assertEquals ( $result, $actual->getResults (), 'Result mismatch' );

    $givenFlags = $actual->getFlags ()->getAll ();
    sort ( $givenFlags );

    $this->assertEquals ( $asserts, $actual->getAsserts ()->getMap (), 'Assert mismatch' );
    $this->assertSame ( $flags, $givenFlags, 'Flag mismatch' );
  }
  
  /**
   *
   */
  private function _compareIterator ( $actual, $result, $asserts, $flags ) {
  
    $results = $actual->getResults ();
  
    if ( $results instanceof Traversable ) {
    
        $this->assertEquals (
            $result,
            iterator_to_array ( $actual->getResults () ),
            'Result mismatch'
        );
    }
    
    $givenFlags = $actual->getFlags ()->getAll ();
    sort ( $givenFlags );

    $this->assertEquals ( $asserts, $actual->getAsserts ()->getMap (), 'Assert mismatch' );
    $this->assertSame ( $flags, $givenFlags, 'Flag mismatch' );
  }
}
