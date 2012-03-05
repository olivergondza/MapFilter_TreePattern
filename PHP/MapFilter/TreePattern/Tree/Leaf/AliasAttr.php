<?php
/**
 * Attr Pattern Alias attribute.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/Attr.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/AliasAttr/DisallowedFollowerException.php';

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_AliasAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
final class MapFilter_TreePattern_Tree_Leaf_AliasAttr extends
    MapFilter_TreePattern_Tree_Leaf_Attr
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Array|ArrayAccess             &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied or not.
     *
     * @since $NEXT$
     */
    public function satisfy(&$query, MapFilter_TreePattern_Asserts $asserts)
    {
    
        $result = parent::satisfy($query, $asserts);
        
        if (!$result->isValid()) return $result;
        
        $value = $this->_removeAttr($query);
        
        $this->_satisfyFollowers($query, $asserts, $value);

        return $result;
    }
    
    /**
     * Satisfy aliases using original attribute value
     *
     * @param Array|ArrayAccess &$query   A query to filter.
     * @param Array             &$asserts Asserts.
     * @param Mixed             $value    A value of alias attribute.
     *
     * @return null
     *
     * @since $NEXT$
     */
    private function _satisfyFollowers(&$query, &$asserts, $value)
    {
    
        foreach ($this->content as $follower) {
      
            $query[ $follower->getAttribute() ] = $value;
            $follower->satisfy($query, $asserts);
        }
    }
    
    /**
     * Remove attribute and fetch its value.
     *
     * @param Array|ArrayAccess &$query A query to filter.
     *
     * @return Mixed Attribute that was removed.
     *
     * @since $NEXT$
     */
    private function _removeAttr(&$query)
    {

        $attr = $this->attribute->getAttribute();

        if (array_key_exists($attr, $query)) {

            $value = $query[ $attr ];
            unset ($query[ $attr ]);
        }

        return $value;
    }
    
    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing result
     *
     * @return Array
     *
     * @since $NEXT$
     */
    public function pickUp($result)
    {

        if (!$this->isSatisfied()) return Array();
        
        $result = (Array) $result;

        foreach ($this->getContent() as $follower) {

            $result = array_merge(
                $result,
                $follower->pickUp($result)
            );
        }

        return $result;
    }
}
