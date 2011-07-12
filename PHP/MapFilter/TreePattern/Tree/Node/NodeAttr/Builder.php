<?php
/**
 * Class to load Pattern tree from xml.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Node/NodeAttr.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Node_NodeAttr_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Node_NodeAttr_Builder extends
    MapFilter_TreePattern_Tree_Node_Builder
{

    public $name;
    public $iterator;

    /**
     * Set attribute.
     *
     * @param String $name An attribute to set.
     *
     * @return null
     *
     * @since 0.4
     */
    public function setAttr($name)
    {
    
        $this->name = $name;
    }
    
    /**
     * Set iterator.
     *
     * @param String $depth An iterator value to set.
     *
     * @return null
     *
     * @since 0.5.2
     */
    public function setIterator($depth)
    {
    
        $this->iterator = $depth;
    }

    /**
     * Build tree element
     *
     * @return MapFilter_TreePattern_Tree_Node_Some Tree Element
     */
    public function build()
    {
    
        return new MapFilter_TreePattern_Tree_Node_NodeAttr($this);
    }
}
