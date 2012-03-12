<?php
/**
 * TreePattern filtering result builder
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
 * TreePattern filtering result builder
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Result_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Result_Builder
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
     * Create Builder instance possibly initialized with $result
     *
     * @param MapFilter_TreePattern_Result $result Initialization result.
     *
     * @since $NEXT$
     */
    public function __construct(MapFilter_TreePattern_Result $result = null)
    {
    
        if ($result) {

            $this->putResult($result);
        }
    }

    /**
     * Put result object.
     *
     * @param MapFilter_TreePattern_Result $result A result to put.
     *
     * @return MapFilter_TreePattern_Result_Builder
     *
     * @since $NEXT$
     */
    public function putResult(MapFilter_TreePattern_Result $result)
    {

        $this->putAsserts($result->getAsserts());
        $this->putFlags($result->getFlags());

        return $this;
    }
    
    /**
     * Put asserts.
     *
     * @param MapFilter_TreePattern_Asserts|Null $asserts Asserts to put.
     *
     * @return MapFilter_TreePattern_Result_Builder
     *
     * @since $NEXT$
     */
    public function putAsserts(MapFilter_TreePattern_Asserts $asserts = null)
    {

        if ($asserts === null) return $this;

        $this->_asserts = ($this->_asserts === null)
            ? $asserts
            : $this->_asserts->combine(Array($asserts))
        ;
        
        return $this;
    }
    
    /**
     * Put flags.
     *
     * @param MapFilter_TreePattern_Flags|Null $flags Flags to put.
     *
     * @return MapFilter_TreePattern_Result_Builder
     *
     * @since $NEXT$
     */
    public function putFlags(MapFilter_TreePattern_Flags $flags = null)
    {
    
        if ($flags === null) return $this;
    
        $this->_flags = ($this->_flags === null)
            ? $flags
            : $this->_flags->combine(Array($flags))
        ;
        
        return $this;
    }
    
    /**
     * Get current asserts
     *
     * @return MapFilter_TreePattern_Asserts
     *
     * @since $NEXT$
     */
    public function getAsserts()
    {
    
        if ($this->_asserts === null) {
        
            $this->_asserts = new MapFilter_TreePattern_Asserts;
        }
        
        return $this->_asserts;
    }
    
    /**
     * Get current flags
     *
     * @return MapFilter_TreePattern_Flags
     *
     * @since $NEXT$
     */
    public function getFlags()
    {
    
        if ($this->_flags === null) {
        
            $this->_flags = new MapFilter_TreePattern_Flags;
        }
    
        return $this->_flags;
    }
    
    /**
     * Instantiate result object
     *
     * @param Bool  $valid  Valid or not.
     *
     * @return MapFilter_TreePattern_Result
     *
     * @since $NEXT$
     */
    public function build($valid)
    {
    
        return new MapFilter_TreePattern_Result(
            null, $this->getAsserts(), $this->getFlags(), $valid
        );
    }
}
