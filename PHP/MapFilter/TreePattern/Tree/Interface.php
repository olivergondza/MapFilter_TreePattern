<?php
/**
 * MapFilter_TreePattern_Tree Interface.
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

/**
 * MapFilter_TreePattern_Tree Interface.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Interface
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
interface MapFilter_TreePattern_Tree_Interface
{
  
    /**
     * Get node followers reference.
     *
     * @return    Array           Node content reference.
     *
     * @since     0.4
     */
    public function &getContent();
    
    /**
     * Set TreePattern.
     *
     * @param MapFilter_TreePattern $pattern A pattern to set.
     *
     * @return    MapFilter_TreePattern
     *
     * @since     0.5.3
     */
    public function setTreePattern(MapFilter_TreePattern $pattern);
    
    /**
     * Create new tree instance.
     *
     * @param MapFilter_TreePattern_Tree_Builder $builder A builder to use.
     *
     * @return    MapFilter_TreePattern_Tree_Interface
     *
     * @see       setAssert(), setFlag(), setValueFilter(), setValuePattern(),
     *            setContent(), setDefault() or setAttribute()
     *
     * @since     0.4
     */
    public function __construct(MapFilter_TreePattern_Tree_Builder $builder);
    
    /**
     * Make copy of the node.
     *
     * @return    MapFilter_TreePattern_Tree_Interface
     *
     * @since     0.4
     */
    public function __clone();
    
    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Array|ArrayAccess             &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return    Bool                    Satisfied or not.
     *
     * @since     0.4
     */
    public function satisfy(&$query, MapFilter_TreePattern_Asserts $asserts);
    
    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing result
     *
     * @return    Array
     *
     * @since     0.3
     */
    public function pickUp($result);
    
    /**
     * Get filtering flags.
     *
     * @param MapFilter_TreePattern_Flags $flags Existing flags
     *
     * @return null
     *
     * @since     0.5.1
     */
    public function pickUpFlags(MapFilter_TreePattern_Flags $flags);
    
    /**
    * Possible iterator values.
    *
    * @since      0.5.2
    * @{
    */
    const ITERATOR_VALUE_NO = 'no';
    const ITERATOR_VALUE_YES = 'yes';
    /**@}*/
}
