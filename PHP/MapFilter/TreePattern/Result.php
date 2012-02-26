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
     * Pattern results
     *
     * @var Mixed $_results.
     *
     * @since $NEXT$
     */
    private $_results;
    
    /**
     * Pattern valid
     *
     * @var bool $_valid
     *
     * @since $NEXT$
     */
    private $_valid;

    /**
     * Create MapFilter_TreePattern_Result
     *
     * @param mixed                         $results Pattern results.
     * @param MapFilter_TreePattern_Asserts $asserts Parsing asserts.
     * @param MapFilter_TreePattern_Flags   $flags   Pattern flags.
     * @param bool                          $valid   Pattern satisfied.
     */
    public function __construct(
        $results,
        MapFilter_TreePattern_Asserts $asserts,
        MapFilter_TreePattern_Flags $flags,
        $valid
    ) {
        assert(is_bool($valid));
    
        $this->_results = $results;
        $this->_asserts = $asserts;
        $this->_flags = $flags;
        $this->_valid = $valid;
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
        return $this->_results;
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
        return $this->_flags;
    }
    
    /**
     * Determine whether the data are valid.
     *
     * @return bool Data valid
     *
     * @since $NEXT$
     */
    public function isValid ()
    {
        return $this->_valid;
    }
    
    public function combine(Array $subResults)
    {
        $asserts = Array();
        $flags = Array();
        foreach ($subResults as $result) {
        
            assert($result instanceof MapFilter_TreePattern_Result);
        
            $asserts[] = $result->_asserts;
            $flags[] = $result->_flags;
        }
        
        $result = clone $this;
        $result->_asserts = $this->_asserts->combine($asserts);
        $result->_flags = $this->_flags->combine($flags);
        
        return $result;
    }
}
