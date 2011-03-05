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
 * @since    0.1
 */

/** @cond       PROGRAMMER */

require_once 'PHP/MapFilter/InvalidStructureException.php';

require_once dirname ( __FILE__ ) . '/TreePattern/Asserts.php';
require_once dirname ( __FILE__ ) . '/TreePattern/Flags.php';

require_once dirname ( __FILE__ ) . '/TreePattern/AssertInterface.php';
require_once dirname ( __FILE__ ) . '/TreePattern/FlagInterface.php';
require_once dirname ( __FILE__ ) . '/TreePattern/ResultInterface.php';

require_once dirname ( __FILE__ ) . '/TreePattern/InvalidPatternNameException.php';

require_once dirname ( __FILE__ ) . '/TreePattern/Xml.php';

/** @endcond */

/**
 * Class to load and hold Pattern tree.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.1
 */
class MapFilter_TreePattern implements
    MapFilter_TreePattern_AssertInterface,
    MapFilter_TreePattern_FlagInterface,
    MapFilter_TreePattern_ResultInterface
{

  /**
   * Pattern tree itself.
   *
   * @since     0.1
   *
   * @var       MapFilter_TreePattern_Tree      $_patternTree
   * @see       __construct()
   */
  private $_patternTree = NULL;

  /**
   * Used tree.
   *
   * @since     0.5
   *
   * @var       MapFilter_TreePattern_Tree      $_tempTree
   * @see       parse()
   */
  private $_tempTree = NULL;
  
  /**
   * Side patterns.
   *
   * Pattern trees to attach.
   *
   * @since     0.5.3
   *
   * @var       Array                           $_sidePatterns
   * @see       attachPattern()
   */
  private $_sidePatterns = Array ();

  /**
   * Validated data.
   *
   * @since     0.5
   *
   * @var       Array|ArrayAccess               $_results
   * @see       getResults(), parse()
   */
  private $_results = Array ();
  
  /**
   * Validation asserts.
   *
   * @since     0.5
   *
   * @var       MapFilter_TreePattern_Asserts   $_asserts
   * @see       getAsserts(), parse()
   */
  private $_asserts = NULL;
  
  /**
   * Validation flags.
   *
   * @since     0.5
   *
   * @var       MapFilter_TreePattern_Flags     $_flags
   * @see       getFlags(), parse()
   */
  private $_flags = NULL;

  /** @cond     INTERNAL */

  /**
   * Data is url.
   *
   * @since     0.1
   *
   * Load data from file
   */
  const DATA_IS_URL = TRUE;
  
  /**
   * Data is string.
   *
   * @since     0.1
   *
   * Load data from string
   */
  const DATA_IS_STRING = FALSE;
  
  /** @endcond */
  
  /**
   * Main pattern name.
   *
   * @since     $NEXT$
   */
  const MAIN_PATTERN = 'main';

  /**
   * Simple Factory Method to load data from XML string.
   *
   * @since     0.1
   *
   * @param     String                  $xmlSource      Pattern string.
   *
   * @return    MapFilter_TreePattern   Pattern created from $xmlSource string
   *
   * @see       fromFile(), MapFilter_TreePattern_Xml::load()
   */
  public static function load ( $xmlSource ) {
    
    assert ( is_string ( $xmlSource ) );
    
    return MapFilter_TreePattern_Xml::load ( $xmlSource );
  }

  /**
   * Simple Factory Method to loading data from XML file.
   *
   * @since     0.1
   *
   * @param     String                  $url    XML pattern file.
   *
   * @return    MapFilter_TreePattern   Pattern created from $url file
   * 
   * @see       load(), MapFilter_TreePattern_Xml::fromFile()
   */
  public static function fromFile ( $url ) {
  
    assert ( is_string ( $url ) );
  
    return MapFilter_TreePattern_Xml::fromFile ( $url );
  }
  
  /**
   * Create a Pattern from the Pattern_Tree object.
   *
   * @since     0.1
   *
   * @note New object is created with @b copy of given patternTree
   *
   * @param     MapFilter_TreePattern_Tree      $patternTree    A tree to use
   * @param     Array                           $sidePatterns   A side patterns to attach.
   *
   * @return    MapFilter_TreePattern           Created Pattern
   *
   * @see       load(), fromFile()
   */
  public function __construct (
      MapFilter_TreePattern_Tree $patternTree, Array $sidePatterns
  ) {

    if ( $patternTree !== NULL ) {

      $patternTree->setTreePattern ( $this );
      $this->setPattern ( $patternTree );
    }
    
    if ( $sidePatterns ) {
     
      $this->setSidePatterns ( $sidePatterns );
    }
  }

  /**
   * Clone pattern tree recursively.
   *
   * @since     0.1
   *
   * @note Deep cloning is used thus new copy of patternTree is going to be
   * created
   *
   * @return    MapFilter_TreePattern   A new clone.
   */
  public function __clone () {

    if ( $this->_patternTree !== NULL ) {
     
      $this->_patternTree = clone $this->_patternTree;
    }
    
    foreach ( $this->_sidePatterns as &$pattern ) {

      assert ( $pattern instanceof MapFilter_TreePattern_Tree_Interface );

      $pattern = clone $pattern;
    }
  }
  
  /**
   * Get results.
   *
   * Get parsed query from latest parsing process.
   *
   * @since     0.5
   *
   * @return    Mixed                           Parsing results.
   */
  public function getResults () {

    return $this->_tempTree->pickUp ( Array () );
  }
  
  /**
   * Get flags
   *
   * Return flags that was sat during latest parsing process.
   *
   * @since     0.5
   *
   * @return    MapFilter_TreePattern_Flags     Parsing flags.
   */
  public function getFlags () {
  
    if ( $this->_flags === NULL ) {
  
      $this->_flags = new MapFilter_TreePattern_Flags ();
      $this->_tempTree->pickUpFlags ( $this->_flags );
    }
    
    return $this->_flags;
  }
  
  /**
   * Get validation assertions.
   *
   * Return validation asserts that was raised during latest parsing process.
   *
   * @since     0.5
   *
   * @return    MapFilter_TreePattern_Asserts   Parsing asserts.
   */
  public function getAsserts () {
  
    return $this->_asserts;
  }

  /**
   * Set Pattern tree.
   *
   * @since     0.5.3
   *
   * @param     Array           $pattern        Pattern to set.
   *
   * @return    MapFilter_TreePattern
   */
  public function setPattern (
      MapFilter_TreePattern_Tree_Interface $pattern
  ) {
  
    $pattern->setTreePattern ( $this );
    $this->_sidePatterns[ self::MAIN_PATTERN ]
        = $this->_patternTree = clone $pattern;
    
    return $this;
  }

  /** @cond     PROGRAMMER */

  /**
   * Set side patterns.
   *
   * @since     0.5.3
   *
   * @param     Array           $sidePatterns           Patterns to set.
   *
   * @return    MapFilter_TreePattern
   */
  public function setSidePatterns ( Array $sidePatterns ) {
  
    foreach ( $sidePatterns as $pattern ) {
    
      $pattern->setTreePattern ( $this );
    }
  
    $this->_sidePatterns = array_merge (
        $this->_sidePatterns,
        $sidePatterns
    );
    
    return $this;
  }

  /**
   * Get clone of side pattern by its name
   *
   * @since     0.5.3
   *
   * @param     String          $sidePatternName        A pattern name.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   * @throws    MapFilter_TreePattern_InvalidPatternNameException
   */
  public function getSidePattern ( $sidePatternName ) {
  
    assert ( is_string ( $sidePatternName ) );
    
    if ( array_key_exists ( $sidePatternName, $this->_sidePatterns ) ) {
    
      return clone $this->_sidePatterns[ $sidePatternName ];
    }

    $ex = new MapFilter_TreePattern_InvalidPatternNameException ();
    throw $ex->setName ( $sidePatternName );
  }

  /**
   * Clean up object storage.
   *
   * @since     0.5
   *
   * This enables to parse multiple queries with the same pattern with no need 
   * to re-instantiate the object.
   */
  private function _cleanup () {
  
    $this->_results = NULL;
    $this->_asserts = new MapFilter_TreePattern_Asserts ();
    $this->_flags = NULL;
    $this->_tempTree = NULL;
  }
  
  /**
   * Parse the given query against the pattern.
   *
   * @since     0.5
   *
   * @param     Array|ArrayAccess       $query          A user query.
   *
   * @throws    MapFilter_InvalidStructureException
   */
  public function parse ( $query ) {
  
    if ( !self::isMap ( $query )  ) {
    
      throw new MapFilter_InvalidStructureException ();
    }
  
    $this->_cleanup ();
  
    $this->_tempTree = clone $this->_patternTree;
  
    $this->_tempTree->satisfy ( $query, $this->_asserts );   
  }
  
  /**
   * Determine whether the parameter is a Map.
   *
   * @since     $NEXT$
   *
   * @param     mixed   $mapCandidate
   *
   * @return    Bool
   */
  public static function isMap ( $mapCandidate ) {
  
    if ( is_array ( $mapCandidate ) ) return TRUE;
    
    return $mapCandidate instanceof ArrayAccess;
  }
  
  /**
   * Determine whether the parameter is an Iterator
   *
   * @since     $NEXT$
   *
   * @param     mixed   $iteratorCandidate
   *
   * @return    Bool
   */
  public static function isIterator ( $iteratorCandidate ) {
  
    if ( is_array ( $iteratorCandidate ) ) return TRUE;
  
    return $iteratorCandidate instanceof Iterator;
  }
  
  /** @endcond */
}