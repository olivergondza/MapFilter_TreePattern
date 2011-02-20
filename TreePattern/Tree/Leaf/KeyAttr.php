<?php
/**
 * KeyAttr Pattern node.
 *
 * PHP Version 5.1.0
 *
 * This file is part of MapFilter package.
 *
 * MapFilter is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 *                
 * MapFilter is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
 * License for more details.
 *                              
 * You should have received a copy of the GNU Lesser General Public License
 * along with MapFilter.  If not, see <http://www.gnu.org/licenses/>.
 *                              
 * @category Pear
 * @package  MapFilter_TreePattern
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */

require_once dirname ( __FILE__ ) . '/Attr.php';

require_once dirname ( __FILE__ ) . '/../Attribute.php';

/**
 * MapFilter pattern tree KeyAttribute node.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_KeyAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
final class
    MapFilter_TreePattern_Tree_Leaf_KeyAttr
extends
    MapFilter_TreePattern_Tree_Leaf_Attr
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

  /**
   * Instantiate attribute
   *
   * @since     $NEXT$
   */
  public function __construct () {
  
    $this->setSetters ( Array (
        'content' => 'setContent'
    ) );
  
    parent::__construct ();
  }

  /**
   * Fluent Method; Set content.
   *
   * @since     0.5.2
   *
   * @param     Array           $content                A content to set.
   *
   * @return    self
   */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
   * Satisfy certain node type and let its followers to get satisfied.
   *
   * @since     0.4
   *
   * @param     Array|ArrayAccess               &$query         A query to filter.
   * @param     MapFilter_TreePattern_Asserts   $asserts        Asserts.
   *
   * @return    Bool                    Satisfied or not.
   *
   * Find a follower with a valueFilter that fits and try to satisfy it.
   */
  public function satisfy ( &$query, MapFilter_TreePattern_Asserts $asserts ) {
  
    assert ( MapFilter_TreePattern::isMap ( $query ) );

    $oldAsserts = $asserts;

    $satisfied = parent::satisfy ( $query, $asserts );
    
    if ( !$satisfied ) return FALSE;

    $asserts = $oldAsserts;

    return $this->satisfied = $this->_satisfyFollowers (
        $query, $asserts
    );
  }
  
  /**
   * Satisfy node followers.
   *
   * @since     $NEXT$
   *
   * @param     Mixed           &$query                 A query.
   * @param     MapFilter_TreePattern_Asserts           $asserts               Assertions.
   *
   * @return    Bool
   */
  private function _satisfyFollowers (
      &$query, MapFilter_TreePattern_Asserts $asserts
  ) {
  
    $value = $this->attribute->getValue ();

    $satisfied = FALSE;
    if ( is_array ( $value ) ) {

      foreach ( $value as $singleCandidate ) {

        $satisfied |= (Bool) $this->_satisfyFittingFollower (
            $query, $asserts, $singleCandidate
        );
      }
    } else {
     
      $satisfied = (Bool) $this->_satisfyFittingFollower (
          $query, $asserts, $value
      );
    }
    
    if ( !$satisfied ) {

      $this->setAssertValue ( $asserts, $value );
    }
    
    return $satisfied;
  }
  
  /**
   * Find a fitting follower, let it satisfy and set value or assertion.
   *
   * @since     0.5.2
   *
   * @param     Mixed           &$query                 A query.
   * @param     MapFilter_TreePattern_Asserts           $asserts               Assertions.
   * @param     Mixed           $valueCandidate         
   *
   * @return    Bool
   */
  private function _satisfyFittingFollower (
      &$query,
      MapFilter_TreePattern_Asserts $asserts,
      $valueCandidate
  ) {
  
    $satisfied = FALSE;
    foreach ( $this->getContent () as $follower ) {
    
      $fits = $follower->getValueFilter ()->match (
          (String) $valueCandidate
      );
      
      if ( !$fits ) continue;
      
      $satisfied |= $follower->satisfy (
          $query, $asserts
      );
    }
    
    if ( !$satisfied ) {

      $this->setAssertValue ( $asserts );
    }
      
    return $this->satisfied = $satisfied;
  }
}
