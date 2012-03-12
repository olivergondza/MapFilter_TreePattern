<?php
/**
 * One Pattern node.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Policy.php';

/**
 * MapFilter pattern tree one node.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Node_One
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.3
 */
final class MapFilter_TreePattern_Tree_Node_One extends
    MapFilter_TreePattern_Tree_Policy
{

    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Mixed &$query A query to filter.
     *
     * @return Bool Satisfied or not.
     *
     * Satisfy the node if there is one satisfied follower (any further
     * followers mustn't be satisfied in order to pick up just first one of
     * those).  Mapping CAN'T continue after finding satisfied follower.
     *
     * @since 0.4
     */
    public function satisfy(&$query)
    {

        $builder = MapFilter_TreePattern_Result::builder();

        foreach ($this->getContent() as $follower) {
          
            $result = $follower->satisfy($query);
            if ($result->isValid()) {

                $this->satisfied = true;
                return $builder->putResult($this->createResult())
                    ->putResult($result)
                    ->build($this->satisfied)
                ;
            }
            $builder->putAsserts($result->getAsserts());
        }
        
        $this->satisfied = false;
        return $builder->putResult($this->createResult())
            ->build($this->satisfied)
        ;
    }
}
