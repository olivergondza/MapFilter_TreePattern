<?php
/**
 * TreePattern filtering result
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

require_once 'PHP/MapFilter/PatternInterface.php';

/**
 * TreePattern filtering result
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Result
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Result
{

    /**
     * Parsing flags
     *
     * @var MapFilter_TreePattern_Flags Filtering $_flags.
     *
     * @since $NEXT$
     */
    private $_flags;
    
    /**
     * Parsing asserts
     *
     * @var MapFilter_TreePattern_Asserts Filtering $_asserts.
     *
     * @since $NEXT$
     */
    private $_asserts;
    
    /**
     * Pattern Tree
     *
     * @var MapFilter_TreePattern_Tree Pattern $_tree.
     *
     * @since $NEXT$
     */
    private $_tree;

    /**
     * Create MapFilter_TreePattern_Result
     *
     * @param MapFilter_TreePattern_Tree    $tree    Pattern tree.
     * @param MapFilter_TreePattern_Asserts $asserts Parsing asserts.
     */
    public function __construct(
        MapFilter_TreePattern_Tree $tree,
        MapFilter_TreePattern_Asserts $asserts
    ) {
        $this->_tree = $tree;
        $this->_asserts = $asserts;
    }

    /**
     * Get results.
     *
     * @return Array|ArrayAccess Parsing results.
     *
     * @since $NEXT$
     */
    public function getResults()
    {
        return $this->_tree->pickUp(null);
    }
    
    /**
     * Return validation asserts that was raised.
     *
     * @return MapFilter_TreePattern_Asserts Filtering asserts.
     *
     * @since $NEXT$
     */
    public function getAsserts()
    {
        return $this->_asserts;
    }
    
    /**
     * Return flags that was set.
     *
     * @return MapFilter_TreePattern_Flags Filtering flags.
     *
     * @since $NEXT$
     */
    public function getFlags()
    {
        if ($this->_flags === null) {
      
            $this->_flags = new MapFilter_TreePattern_Flags;
            $this->_tree->pickUpFlags($this->_flags);
        }
        
        return $this->_flags;
    }
}
