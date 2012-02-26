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

require_once 'PHP/MapFilter/TreePattern/Result.php';

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
class MapFilter_TreePattern implements MapFilter_PatternInterface
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
     * Main pattern name.
     *
     * @since     $NEXT$
     */
    const MAIN_PATTERN = 'main';

    /**
     * Create a Pattern from the Pattern_Tree object.
     *
     * @param MapFilter_TreePattern_Tree $patternTree  A tree to use.
     * @param Array                      $sidePatterns A side patterns to attach.
     *
     * @return    MapFilter_TreePattern           Created Pattern
     *
     * @see       MapFilter_TreePattern_Xml
     *
     * @since     0.1
     */
    public function __construct(
        MapFilter_TreePattern_Tree $patternTree, Array $sidePatterns
    ) {

        if ($patternTree !== null) {

            $patternTree->setTreePattern($this);
            $this->_setPattern($patternTree);
        }
      
        if ($sidePatterns) {
       
            $this->_setSidePatterns($sidePatterns);
        }
    }
    
    /**
     * Set Pattern tree.
     *
     * @param MapFilter_TreePattern_Tree_Interface $pattern Pattern to set.
     *
     * @return    MapFilter_TreePattern
     *
     * @since     0.5.3
     */
    private function _setPattern(
        MapFilter_TreePattern_Tree_Interface $pattern
    ) {
    
        $pattern->setTreePattern($this);
        $this->_patternTree = clone $pattern;
        $this->_sidePatterns[ self::MAIN_PATTERN ] = $this->_patternTree;
      
        return $this;
    }

    /**
     * Set side patterns.
     *
     * @param Array $sidePatterns Patterns to set.
     *
     * @return    MapFilter_TreePattern
     *
     * @since     0.5.3
     */
    private function _setSidePatterns(Array $sidePatterns)
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
    
        return $iteratorCandidate instanceof Traversable;
    }
 
    /**
     * Parse the given query against the pattern.
     *
     * @param Mixed $query A user query.
     *
     * @return MapFilter_TreePattern_Result
     *
     * @throws MapFilter_InvalidStructureException
     *
     * @since     0.5
     */
    public function parse($query)
    {

        $asserts = new MapFilter_TreePattern_Asserts;
        $flags = new MapFilter_TreePattern_Flags;

        $tempTree = clone $this->_patternTree;
        $valid = $tempTree->satisfy($query, $asserts);
        
        $tempTree->pickUpFlags($flags);
        
        return new MapFilter_TreePattern_Result(
            $tempTree->pickUp(null), $asserts, $flags, $valid
        );
    }
 
    /** @cond     PROGRAMMER */

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
    /** @endcond */
}
