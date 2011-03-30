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

require_once 'PHP/TreePattern/Tree.php';

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
     * Instantiate
     *
     * @since   0.3
     */
    public function __construct()
    {
    
        $this->setSetters(
            Array(
                'content' => 'setContent'
            )
        );
    
        parent::__construct();
    }

    /**
     * Fluent Method; Set content.
     *
     * @param Array $content A content to set.
     *
     * @return    MapFilter_TreePattern_Tree_Node
     *
     * @since     0.3
     */
    public function setContent(Array $content)
    {
     
        $this->content = $content;
        return $this;
    }
    
    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing results.
     *
     * @return    Array
     *
     * @since     0.3
     */
    public function pickUp(Array $result)
    {

        if (!$this->isSatisfied()) return Array();
      
        foreach ($this->getContent() as $follower) {

            $result = array_merge(
                $result,
                $follower->pickUp($result)
            );
        }
        
        return $result;
    }
}
