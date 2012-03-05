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

    protected $data = null;
    private $_lowerBoundary = null;
    private $_upperBoundary = null;
    private $_follower;

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
            
                $this->data[ $key ] = $follower->pickUp($result);
            }
        }

        return $this->data;
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

        if (!MapFilter_TreePattern::isIterator($query)) {
        
            return $this->createResult($asserts);
        }

        $result = $this->_dispatchFollowers($query, $asserts);
        
        return $this->createResult($asserts)
            ->getBuilder()
            ->putResult($result)
            ->build($this->data, $this->satisfied)
        ;
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
    
        $this->data = (!is_array($query))
            ? new ArrayIterator()
            : Array()
        ;
        
        $this->_follower = $this->getFollower();
        $this->content = Array();

        return $this->_validateFollowers($query, $asserts);
    }
    
    /**
     * Validate itterator according to pattern
     *
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return  Bool
     *
     * @since     $NEXT$
     */
    private function _validateFollowers (
        &$query,
        MapFilter_TreePattern_Asserts $asserts
    ) {
    
        $builder = MapFilter_TreePattern_Result::builder();
    
        $length = 0;
        foreach ($query as $key => $iteratorItem) {
        
            $length++;

            if ($this->_isOutOfRange($length)) break;

            if ($this->_follower) {

                $result = $this->_isValid($key, $iteratorItem, $asserts);

                if (!$result->isValid()) {
            
                    $builder->putAsserts($result->getAsserts());
                    $length--;
                    continue;  
                }
                
                $builder->putFlags($result->getFlags());
            }
            
            $this->data[ $key ] = $iteratorItem;
        }
        
        if ($length < $this->_lowerBoundary) {
        
            $this->satisifed = false;
            
            $childResult = $builder->build($this->data, $this->satisfied);
            return $this->createResult($asserts)
                ->getBuilder()
                ->putAsserts($childResult->getAsserts())
                ->build($this->data, $this->satisfied)
            ;
        }
        
        $this->satisfied = true;
        return $builder->build($this->data, $this->satisfied);
    }
    
    /**
     * Determine whether an iterator element is valid according to the pattern
     *
     * @param Mixed                         $key     Iterator key.
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool
     *
     * @since $NEXT$
     */
    private function _isValid(
        $key,
        &$query,
        MapFilter_TreePattern_Asserts $asserts
    ) {
    
        $pattern = clone $this->_follower;
        
        $result = $pattern->satisfy($query, $asserts);
        $builder = $result->getBuilder();
        
        if ($result->isValid()) {
        
            $this->content[ $key ] = $pattern;
            $builder->putFlags($result->getFlags());
        } else {

            $builder->putAsserts($result->getAsserts());
        }

        return $builder->build($this->data, $result->isValid());
    }
    
    /**
     * Determine whether the length is out of range
     *
     * @param Int $length Current length.
     *
     * @return Bool
     *
     * @since $NEXT$
     */
    private function _isOutOfRange($length)
    {
    
        assert(is_int($length));

        if (!is_int($this->_upperBoundary)) return false;
    
        return $length > $this->_upperBoundary;
    }
}
