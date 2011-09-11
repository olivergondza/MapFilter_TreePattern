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

require_once 'PHP/MapFilter/InvalidStructureException.php';

require_once 'PHP/MapFilter/TreePattern/Asserts.php';
require_once 'PHP/MapFilter/TreePattern/Flags.php';

require_once 'PHP/MapFilter/TreePattern/AssertInterface.php';
require_once 'PHP/MapFilter/TreePattern/FlagInterface.php';
require_once 'PHP/MapFilter/TreePattern/ResultInterface.php';

require_once 'PHP/MapFilter/TreePattern/InvalidPatternNameException.php';

require_once 'PHP/MapFilter/TreePattern/Xml.php';

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
    private $_patternTree = null;

    /**
     * Used tree.
     *
     * @since     0.5
     *
     * @var       MapFilter_TreePattern_Tree      $_tempTree
     * @see       parse()
     */
    private $_tempTree = null;
    
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
    private $_sidePatterns = Array();

    /**
     * Validated data.
     *
     * @since     0.5
     *
     * @var       Array|ArrayAccess               $_results
     * @see       getResults(), parse()
     */
    private $_results = Array();
    
    /**
     * Validation asserts.
     *
     * @since     0.5
     *
     * @var       MapFilter_TreePattern_Asserts   $_asserts
     * @see       getAsserts(), parse()
     */
    private $_asserts = null;
    
    /**
     * Validation flags.
     *
     * @since     0.5
     *
     * @var       MapFilter_TreePattern_Flags     $_flags
     * @see       getFlags(), parse()
     */
    private $_flags = null;

    /** @cond     INTERNAL */

    /**
     * Data is url.
     *
     * @since     0.1
     *
     * Load data from file
     */
    const DATA_IS_URL = true;
    
    /**
     * Data is string.
     *
     * @since     0.1
     *
     * Load data from string
     */
    const DATA_IS_STRING = false;
    
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
     * @param String $xmlSource Pattern string.
     *
     * @return MapFilter_TreePattern Pattern created from $xmlSource string
     *
     * @see       MapFilter_TreePattern_Xml::fromFile(), MapFilter_TreePattern_Xml::load()
     *
     * @since     0.1
     * @deprecated since $NEXT$
     */
    public static function load($xmlSource)
    {
      
        trigger_error(__CLASS__ . '::' . __FUNCTION__ . ' is deprecated');
      
        assert(is_string($xmlSource));
      
        return MapFilter_TreePattern_Xml::load($xmlSource);
    }

    /**
     * Simple Factory Method to loading data from XML file.
     *
     * @param String $url XML pattern file.
     *
     * @return MapFilter_TreePattern Pattern created from $url file
     * 
     * @see       MapFilter_TreePattern_Xml::load(), MapFilter_TreePattern_Xml::fromFile()
     *
     * @since     0.1
     * @deprecated since $NEXT$
     */
    public static function fromFile($url)
    {
    
        trigger_error(__CLASS__ . '::' . __FUNCTION__ . ' is deprecated');
    
        assert(is_string($url));
    
        return MapFilter_TreePattern_Xml::fromFile($url);
    }
    
    /**
     * Create a Pattern from the Pattern_Tree object.
     *
     * @param MapFilter_TreePattern_Tree $patternTree  A tree to use.
     * @param Array                      $sidePatterns A side patterns to attach.
     *
     * @return    MapFilter_TreePattern           Created Pattern
     *
     * @note New object is created with @b copy of given patternTree
     *
     * @see       load(), fromFile()
     *
     * @since     0.1
     */
    public function __construct(
        MapFilter_TreePattern_Tree $patternTree, Array $sidePatterns
    ) {

        if ($patternTree !== null) {

            $patternTree->setTreePattern($this);
            $this->setPattern($patternTree);
        }
      
        if ($sidePatterns) {
       
            $this->setSidePatterns($sidePatterns);
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
    public function __clone()
    {

        if ($this->_patternTree !== null) {
       
            $this->_patternTree = clone $this->_patternTree;
        }
      
        foreach ($this->_sidePatterns as &$pattern) {

            assert($pattern instanceof MapFilter_TreePattern_Tree_Interface);

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
    public function getResults()
    {

        return $this->_tempTree->pickUp(null);
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
    public function getFlags()
    {
    
        if ($this->_flags === null) {
      
            $this->_flags = new MapFilter_TreePattern_Flags;
            $this->_tempTree->pickUpFlags($this->_flags);
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
    public function getAsserts()
    {
    
        return $this->_asserts;
    }
    
    /**
     * Get MapFilter instance initialized ba pattern and query
     *
     * @param Mixed $query A query to use
     *
     * @return MapFilter_Interface New filter instance
     *
     * @since   $NEXT$
     */
    public function getFilter($query)
    {
    
        return new MapFilter($this, $query);
    }

    /**
     * Set Pattern tree.
     *
     * @param Array $pattern Pattern to set.
     *
     * @return    MapFilter_TreePattern
     *
     * @since     0.5.3
     */
    public function setPattern(
        MapFilter_TreePattern_Tree_Interface $pattern
    ) {
    
        $pattern->setTreePattern($this);
        $this->_patternTree = clone $pattern;
        $this->_sidePatterns[ self::MAIN_PATTERN ] = $this->_patternTree;
      
        return $this;
    }

    /** @cond     PROGRAMMER */

    /**
     * Set side patterns.
     *
     * @param Array $sidePatterns Patterns to set.
     *
     * @return    MapFilter_TreePattern
     *
     * @since     0.5.3
     */
    public function setSidePatterns(Array $sidePatterns)
    {
    
        foreach ($sidePatterns as $pattern) {
      
            $pattern->setTreePattern($this);
        }
    
        $this->_sidePatterns = array_merge(
            $this->_sidePatterns,
            $sidePatterns
        );
      
        return $this;
    }

    /**
     * Get clone of side pattern by its name
     *
     * @param String $sidePatternName A pattern name.
     *
     * @return    MapFilter_TreePattern_Tree_Interface
     * @throws    MapFilter_TreePattern_InvalidPatternNameException
     *
     * @since     0.5.3
     */
    public function getSidePattern($sidePatternName)
    {
    
        assert(is_string($sidePatternName));
      
        if (array_key_exists($sidePatternName, $this->_sidePatterns)) {
      
            return clone $this->_sidePatterns[ $sidePatternName ];
        }

        throw new MapFilter_TreePattern_InvalidPatternNameException(
            $sidePatternName
        );
    }

    /**
     * Clean up object storage.
     *
     * @return  null
     *
     * @since     0.5
     *
     * This enables to parse multiple queries with the same pattern with no need 
     * to re-instantiate the object.
     */
    private function _cleanup()
    {
    
        $this->_asserts = new MapFilter_TreePattern_Asserts;
        $this->_flags = null;
        $this->_results = null;
        $this->_tempTree = null;
    }
    
    /**
     * Parse the given query against the pattern.
     *
     * @param Array|ArrayAccess $query A user query.
     *
     * @return null
     *
     * @throws    MapFilter_InvalidStructureException
     *
     * @since     0.5
     */
    public function parse($query)
    {

        $this->_cleanup();

        $this->_tempTree = clone $this->_patternTree;
        $this->_tempTree->satisfy($query, $this->_asserts);   
    }

    /**
     * Determine whether the parameter is a Map.
     *
     * @param mixed $mapCandidate Map candidate
     *
     * @return    Bool
     *
     * @since     $NEXT$
     */
    public static function isMap($mapCandidate)
    {
    
        if (is_array($mapCandidate)) return true;
      
        return $mapCandidate instanceof ArrayAccess;
    }
    
    /**
     * Determine whether the parameter is an Iterator
     *
     * @param mixed $iteratorCandidate Iterator candidate
     *
     * @return    Bool
     *
     * @since     $NEXT$
     */
    public static function isIterator($iteratorCandidate)
    {
    
        if (is_array($iteratorCandidate)) return true;
    
        return $iteratorCandidate instanceof Iterator;
    }
    
    /** @endcond */
}
