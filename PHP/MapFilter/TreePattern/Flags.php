<?php
/**
 * Filtering flags.
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

/**
 * Filtering flags.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Flags
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Flags
{

    /**
     * Set flags.
     *
     * @since     $NEXT$
     *
     * @var       Array           $_flags
     */
    private $_flags = Array();

    /**
     * Create flags object
     *
     * @param Array $flags Predefined flags
     *
     * @since     $NEXT$
     */
    public function __construct(Array $flags = Array())
    {
    
        $this->_flags = array_unique(array_values($flags));
        sort($this->_flags);
    }

    /**
     * Set new flags.
     *
     * @param String $flagName Flag identifier
     *
     * @return    MapFilter_TreePattern_Flags
     *
     * @since     $NEXT$
     */
    public function set($flagName)
    {
    
        assert(is_string($flagName));
        
        $this->_flags[] = $flagName;
      
        $this->_flags = array_unique($this->_flags);
    
        sort($this->_flags);
    
        return $this;
    }

    /**
     * Get all flags.
     *
     * @return    Array
     *
     * @since     $NEXT$
     */
    public function getAll()
    {

        return $this->_flags;
    }
    
    /**
     * Determine whether the flag is set.
     *
     * @param String $flagName Flag identifier
     *
     * @return    Bool
     *
     * @since     $NEXT$
     */
    public function exists($flagName)
    {
    
        assert(is_string($flagName));
    
        return in_array($flagName, $this->_flags);
    }
    
    /**
     * Combibe asserts with several other assert sets
     *
     * @param Array $assertSets Assert sets to combine
     *
     * @return MapFilter_TreePattern_Asserts
     *
     * @since     $NEXT$
     */
    public function combine(Array $flagSets) {
    
        $result = clone $this;
        foreach ($flagSets as $flags) {
        
            assert($flags instanceof MapFilter_TreePattern_Flags);
        
            $result->_flags += $flags->_flags;
        }
        
        return $result;
    }
}
