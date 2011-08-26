<?php
/**
 * Ancestor of pattern tree nodes.
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
 * @since    0.3
 */

require_once 'PHP/MapFilter/TreePattern/Tree.php';

/**
 * Abstract class for pattern tree node.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Node
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.3
 */
abstract class MapFilter_TreePattern_Tree_Node extends
    MapFilter_TreePattern_Tree
{

    /**
     * Element data
     *
     * @var     mixed   $data
     *
     * @since   $NEXT$
     */
    protected $data = null;

    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing results.
     *
     * @return    Array
     *
     * @since     0.3
     */
    public function pickUp($result)
    {

        if (!$this->isSatisfied()) return null;
      
        if ($result === null && $this->data !== null) {
        
            $result = ($this->data instanceof ArrayAccess)
                ? new ArrayObject
                : Array ()
            ;
        }
      
        foreach ($this->getContent() as $follower) {

            $data = $follower->pickUp($result);
            
            if ( $data === null ) continue;

            foreach ( $data as $key => $val ) {

                $result[ $key ] = $val;
            }
        }
        
        
        
        return $result;
    }
}
