<?php
/**
 * Class to load and hold Pattern tree.
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
 * @since    $NEXT$
 */

require_once 'PHP/TreePattern/Asserts/InvalidInitializationException.php';
require_once 'PHP/TreePattern/Asserts/MissingPropertyException.php';

/**
 * Class to load and hold Pattern tree.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Asserts
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Asserts {

  /**
   * Assertion path.
   *
   * @since     $NEXT$
   */
  const PATH = 0;
  
  /**
   * Assertion value.
   *
   * @since     $NEXT$
   */
  const VALUE = 1;

  /**
   * Set asserts.
   *
   * @since     $NEXT$
   *
   * @var       Array           $_asserts
   */
  private $_asserts = Array ();
  
  /**
   * Create asserts
   *
   * @since     $NEXT$
   *
   * @param     Array           $asserts
   */
  public function __construct ( Array $asserts = Array () ) {
  
    foreach ( $asserts as $name => $details ) {
    
      if ( is_int ( $name ) && !is_string ( $details ) ) {
      
        throw new MapFilter_TreePattern_Asserts_InvalidInitializationException ();
      }
    
      $key = ( !is_int ( $name ) )
          ? $name
          : $details
      ;
    
      $this->_asserts[ $key ] = ( is_array ( $details ) )
          ? $details
          : Array ()
      ;
    }
    
    ksort ( $this->_asserts );
  }

  /**
   * Set validationAssert including path and value
   *
   * @since     $NEXT$
   *
   * @param     String          $assertName
   * @param     String          $path
   * @param     Mixed           &$value
   *
   * @return    MapFilter_TreePattern_Flags
   */
  public function set ( $assertName, $path = NULL, &$value = NULL ) {
  
    assert ( is_string ( $assertName ) );
    assert ( is_string ( $path ) || is_null ( $path ) );

    $this->_asserts[ $assertName ] = Array ();
    
    if ( $path !== NULL ) {
    
      $this->_asserts[ $assertName ][ self::PATH ] = $path;
    }
    
    if ( $value !== NULL ) {
    
      $this->_asserts[ $assertName ][ self::VALUE ] = $value;
    }
    
    ksort ( $this->_asserts );
    
    return $this;
  }
  
  /**
   * Get all asserts
   *
   * @since     $NEXT$
   *
   * @return    Array
   */
  public function getAll () {

    return array_keys ( $this->_asserts );
  }
  
  /**
   * Determine whether an assert is set
   *
   * @since     $NEXT$
   *
   * @param     String          $assertName
   *
   * @return    Bool
   */
  public function exists ( $assertName ) {
  
    assert ( is_string ( $assertName ) );
  
    return array_key_exists ( $assertName, $this->_asserts );
  }
  
  /**
   * Get assert path
   *
   * @since     $NEXT$
   *
   * @param     String          $assertName
   * 
   * @return    String
   */
  public function getPath ( $assertName ) {
  
    assert ( is_string ( $assertName ) );
  
    $hasVal = $this->exists ( $assertName )
        && array_key_exists ( self::PATH, $this->_asserts[ $assertName ] )
    ;
    
    if ( !$hasVal ) {
    
      $ex = new MapFilter_TreePattern_Asserts_MissingPropertyException ();
      throw $ex->setProperty ( 'path', $assertName );
    }
    
    return $this->_asserts[ $assertName ][ self::PATH ];
  }
  
  /**
   * Get assert value
   *
   * @since     $NEXT$
   *
   * @param     String          $assertName
   *
   * @return    &Mixed
   */
  public function &getValue ( $assertName ) {
  
    assert ( is_string ( $assertName ) );
    
    $hasVal = $this->exists ( $assertName )
        && array_key_exists ( self::VALUE, $this->_asserts[ $assertName ] )
    ;
    
    if ( !$hasVal ) {
    
      $ex = new MapFilter_TreePattern_Asserts_MissingPropertyException ();
      throw $ex->setProperty ( 'value', $assertName );
    }
    
    return $this->_asserts[ $assertName ][ self::VALUE ];
  }
}