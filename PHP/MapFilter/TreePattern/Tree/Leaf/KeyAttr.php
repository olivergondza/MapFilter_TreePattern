<?php
/**
 * KeyAttr Pattern node.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/Attr.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Attribute.php';

/**
 * MapFilter pattern tree KeyAttribute node.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_KeyAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
final class
    MapFilter_TreePattern_Tree_Leaf_KeyAttr
extends
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
     * Find a follower with a valueFilter that fits and try to satisfy it.
     *
     * @since 0.4
     */
    public function satisfy(&$query, MapFilter_TreePattern_Asserts $asserts)
    {
    
        assert(MapFilter_TreePattern::isMap($query));

        $oldAsserts = $asserts;

        $satisfied = parent::satisfy($query, $asserts);
        
        if (!$satisfied) return false;

        $asserts = $oldAsserts;

        return $this->satisfied = $this->_satisfyFollowers(
            $query, $asserts
        );
    }
    
    /**
     * Satisfy node followers.
     *
     * @param Mixed                         &$query  A query.
     * @param MapFilter_TreePattern_Asserts $asserts Assertions.
     *
     * @return Bool
     *
     * @since $NEXT$
     */
    private function _satisfyFollowers(
        &$query, MapFilter_TreePattern_Asserts $asserts
    ) {
    
        $value = $this->attribute->getValue();

        $satisfied = false;
        if (is_array($value)) {

            foreach ($value as $singleCandidate) {

                $satisfied |= (Bool) $this->_satisfyFittingFollower(
                    $query, $asserts, $singleCandidate
                );
            }
        } else {
         
            $satisfied = (Bool) $this->_satisfyFittingFollower(
                $query, $asserts, $value
            );
        }
        
        if (!$satisfied) {

            $this->setAssertValue($asserts, $value);
        }
        
        return $satisfied;
    }
    
    /**
     * Find a fitting follower, let it satisfy and set value or assertion.
     *
     * @param Mixed                         &$query         A query.
     * @param MapFilter_TreePattern_Asserts $asserts        Assertions.
     * @param Mixed                         $valueCandidate Value candidate.
     *
     * @return Bool
     *
     * @since 0.5.2
     */
    private function _satisfyFittingFollower (
        &$query,
        MapFilter_TreePattern_Asserts $asserts,
        $valueCandidate
    ) {
    
        $satisfied = false;
        foreach ($this->getContent() as $follower) {
        
            $fits = $follower->getValueFilter()->match(
                (String) $valueCandidate
            );
            
            if (!$fits) continue;
            
            $satisfied |= $follower->satisfy(
                $query, $asserts
            );
        }
        
        if (!$satisfied) {

            $this->setAssertValue($asserts);
        }
          
        return $this->satisfied = $satisfied;
    }
}
