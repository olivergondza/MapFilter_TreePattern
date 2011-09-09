<?php
/**
 * Value node.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Struct.php';

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Ieerator
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Iterator extends
    MapFilter_TreePattern_Tree_Struct
{

    private $_data = null;

    /**
     * Instantiate iterator element
     *
     * @param MapFilter_TreePattern_Tree_Builder $builder A builder to use.
     *
     * @since     $NEXT$
     */
    public function __construct(MapFilter_TreePattern_Tree_Builder $builder)
    {

        parent::__construct($builder);
    }

    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing results
     *
     * @return    Array
     *
     * @since     $NEXT$
     */
    public function pickUp($result)
    {

        if (!$this->isSatisfied()) return null;

        foreach ($this->getContent() as $key => $follower) {

            if ($follower->isSatisfied()) {
            
                $this->_data[ $key ] = $follower->pickUp($result);
            }
        }

        return $this->_data;
    }
    
    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied or not.
     *
     * @since     $NEXT$
     */
    public function satisfy(&$query, MapFilter_TreePattern_Asserts $asserts)
    {
        assert($this->satisfied === false);

        $this->satisfied = MapFilter_TreePattern::isIterator($query);
        
        if ($this->getContent()) {
        
            if ($this->satisfied) {

                $this->_dispatchFollowers($query, $asserts);
            }
        }
        
        if (!$this->satisfied) {
        
            $this->setAssertValue($asserts);
            return false;
        }

        $this->_data = $query;
        return $this->satisfied;
    }
    
    /**
     * Satisfy followers using new pattern for each follower
     *
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return  null
     *
     * @since     $NEXT$
     */
    private function _dispatchFollowers(
        &$query, MapFilter_TreePattern_Asserts $asserts
    ) {
    
        $content = $this->getContent();
        $follower = $content[ 0 ];
        $this->content = Array ();
        
        foreach ($query as $key => &$iteratorItem) {
        
            $pattern = clone $follower;

            if (!$pattern->satisfy($iteratorItem, $asserts)) {

                unset ($query[ $key ]);
                continue;
            }
            
            $this->content[ $key ] = $pattern;
        }
    }
}
