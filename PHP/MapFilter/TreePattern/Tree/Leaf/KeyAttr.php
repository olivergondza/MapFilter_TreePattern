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
     * @param Mixed &$query A query to filter.
     *
     * @return Bool Satisfied or not.
     *
     * Find a follower with a valueFilter that fits and try to satisfy it.
     *
     * @since 0.4
     */
    public function satisfy(&$query)
    {
    
        assert(MapFilter_TreePattern::isMap($query));

        $result = parent::satisfy($query);
        $this->satisfied = $result->isValid();

        if (!$this->satisfied) {

            return $this->attribute->isPresent()
                ? $this->createResult($query[(String) $this->attribute])
                : $this->createResult()
            ;
        }

        return $this->_satisfyFollowers($query);
    }
    
    /**
     * Satisfy node followers.
     *
     * @param Mixed &$query A query.
     *
     * @return Bool
     *
     * @since $NEXT$
     */
    private function _satisfyFollowers(&$query)
    {
    
        $value = $this->attribute->getValue();

        $builder = MapFilter_TreePattern_Result::builder();
        if (is_array($value)) {

            foreach ($value as $singleCandidate) {

                $result = $this->_satisfyFittingFollower(
                    $query, $singleCandidate
                );
                $builder->putResult($result);
                
                if ($result->isValid()) {
                
                    $this->satisfied = true;
                }
            }
        } else {
         
            $result = $this->_satisfyFittingFollower($query, $value);
            $builder->putResult($result);
            
            $this->satisfied = $result->isValid();
        }

        return $builder->putResult($this->createResult($value))
            ->build($this->satisfied)
        ;
    }
    
    /**
     * Find a fitting follower, let it satisfy and set value or assertion.
     *
     * @param Mixed &$query         A query.
     * @param Mixed $valueCandidate Value candidate.
     *
     * @return Bool
     *
     * @since 0.5.2
     */
    private function _satisfyFittingFollower(&$query, $valueCandidate)
    {
    
        $builder = MapFilter_TreePattern_Result::builder();
    
        $satisfied = false;
        foreach ($this->getContent() as $follower) {
        
            $fits = $follower->getValueFilter()->match(
                (String) $valueCandidate
            );
            
            if (!$fits) continue;
            
            $result = $follower->satisfy($query);
            $builder->putResult($result);
            
            $satisfied |= $result->isValid();
        }
        
        return $builder->build((Bool) $satisfied);
    }
}
