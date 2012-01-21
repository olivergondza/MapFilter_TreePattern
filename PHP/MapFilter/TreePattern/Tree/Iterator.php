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
 * MapFilter/TreePattern pattern tree structural element.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Iterator
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Iterator extends
    MapFilter_TreePattern_Tree_Struct
{

    private $_data = null;
    private $_lowerBoundary = null;
    private $_upperBoundary = null;

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

        $this->_lowerBoundary = $builder->lowerBoundary;
        $this->_upperBoundary = $builder->upperBoundary;
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

        $this->satisfied = $this->_isSatisfied($query, $asserts);
        if (!$this->satisfied) {
        
            $this->setAssertValue($asserts);
        }

        return $this->satisfied;
    }
    
    /**
     * Determinw whether the element is satisfied
     *
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied or not.
     *
     * @since     $NEXT$
     */
    private function _isSatisfied(&$query, MapFilter_TreePattern_Asserts $asserts)
    {
    
        if (!MapFilter_TreePattern::isIterator($query)) return false;
        
        return $this->_dispatchFollowers($query, $asserts);
    }
    
    /**
     * Satisfy followers using new pattern for each follower
     *
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied
     *
     * @since     $NEXT$
     */
    private function _dispatchFollowers(
        &$query, MapFilter_TreePattern_Asserts $asserts
    ) {
    
        $content = $this->getContent();
        $follower = ($content)
            ? $content[ 0 ]
            : null
        ;

        $this->_data = (!is_array($query))
            ? new ArrayIterator()
            : Array()
        ;
        
        $this->content = Array();

        $length = $this->_validateFollowers($query, $asserts, $follower);
        
        return $length >= $this->_lowerBoundary;
    }
    
    /**
     * Validate itterator according to pattern
     *
     * @param Mixed                         &$query   A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts  Asserts.
     * @param MapFilter_TreePattern_Tree    $follower Pattern.
     *
     * @return  Bool
     *
     * @since     $NEXT$
     */
    private function _validateFollowers (
        &$query,
        MapFilter_TreePattern_Asserts $asserts,
        MapFilter_TreePattern_Tree $follower = null
    ) {
    
        $length = 0;
        foreach ($query as $key => $iteratorItem) {
        
            $length++;
            
            if ($this->_isRedundant($length)) return $length - 1;
            
            if (!$this->_isValid($iteratorItem, $key, $asserts, $follower)) {
            
                $length--;
                continue;  
            }
            
            $this->_data[ $key ] = $iteratorItem;
        }
        
        return $length;
    }
    
    /**
     * Determine whether an iterator element is valid according to the pattern
     *
     * @param Mixed                         &$query   A query to filter.
     * @param Mixed                         $key      Iterator key.
     * @param MapFilter_TreePattern_Asserts $asserts  Asserts.
     * @param MapFilter_TreePattern_Tree    $follower Pattern.
     *
     * @return Bool
     *
     * @since $NEXT$
     */
    private function _isValid(
        &$query,
        $key,
        MapFilter_TreePattern_Asserts $asserts,
        MapFilter_TreePattern_Tree $follower = null
    ) {
    
        if (!$follower) return true;
        
        $pattern = clone $follower;
        $followerSatisfied = $pattern->satisfy($query, $asserts);
        
        if ($followerSatisfied) {
        
            $this->content[ $key ] = $pattern;
        }
        
        return $followerSatisfied;
    }
    
    /**
     * Determine whether the element in array is redundant
     *
     * @param Int $length Slice length.
     *
     * @return Bool
     *
     * @since $NEXT$
     */
    private function _isRedundant($length)
    {
    
        assert(is_int($length));

        if (!is_int($this->_upperBoundary)) return false;
    
        return $length > $this->_upperBoundary;
    }
}
