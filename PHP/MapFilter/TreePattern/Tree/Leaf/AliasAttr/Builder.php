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

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/Attr/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/AliasAttr.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_AliasAttr_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Leaf_AliasAttr_Builder extends
    MapFilter_TreePattern_Tree_Leaf_Attr_Builder
{

    /**
     * Fluent Method; Set content.
     *
     * @param Array $content A content to set.
     *
     * @return MapFilter_TreePattern_Tree_Node
     *
     * @since $NEXT$
     */
    public function setContent(Array $content)
    {
    
        foreach ($content as $follower) {
        
            $class = get_class($follower);
        
            if ($class === 'MapFilter_TreePattern_Tree_Key') continue;
          
            throw new MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException(
                $class
            );
        }
       
        $this->content = $content;
    }
    
    public function setName($name)
    {
    
        $this->setAttr($name);
    }

    /**
     * Build tree element
     *
     * @return MapFilter_TreePattern_Tree_Leaf_AliasAttr Tree Element
     */
    public function build()
    {
    
        return new MapFilter_TreePattern_Tree_Leaf_AliasAttr($this);
    }
}
