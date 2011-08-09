<?php
/**
 * NodeAttr Pattern node.
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
 * @since    0.5.3
 */

require_once 'PHP/MapFilter/TreePattern/Tree/Node.php';

/**
 * MapFilter pattern tree NodeAttr node.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Node_NodeAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.3
 */
final class MapFilter_TreePattern_Tree_Node_NodeAttr extends
    MapFilter_TreePattern_Tree_Node
{

    /**
     * Attribute name
     *
     * @since 0.5.3
     *
     * @var String $_attribute
     */
    private $_attribute = null;

    /**
     * Determine whether a value is scalar or an array/iterator.
     *
     * @since 0.5.3
     *
     * @var String $_iterator
     */
    private $_iterator = 0;
    
    /**
     * Copy of an original follower.
     *
     * Kept to be cloned for iterator satisfaction purposes.
     *
     * @since 0.5.3
     *
     * @var MapFilter_TreePattern_Tree_Interface $_follower
     */
    private $_follower = null;

    /**
     * Instantiate
     *
     * @param MapFilter_TreePattern_Tree_Builder $builder A builder to use.
     * 
     * @since 0.5.3
     */
    public function __construct(MapFilter_TreePattern_Tree_Builder $builder)
    {
    
        parent::__construct($builder);
    
        $this->_attribute = $builder->name;
        $this->_iterator = $builder->iterator;
        $this->setContent($builder->content);
    }

    /**
     * Fluent Method; Set content.
     *
     * @param Array $content A content to set.
     *
     * @return MapFilter_TreePattern_Tree_Node_NodeAttr
     *
     * @since 0.5.3
     */
    public function setContent(Array $content)
    {

        if (!$this->attachPattern) {

            self::_assertNonSingleFollower($content);
        }
        
        $this->content = $content;
        $this->_follower = array_shift($content);

        return $this;
    }

    /**
     * Examine content and throw an exception if needed.
     *
     * @param Array $content A content to examine.
     *
     * @return Array
     *
     * @throws MapFilter_TreePattern_NotExactlyOneFollowerException
     *
     * @since 0.5.3
     */
    private function _assertNonSingleFollower(Array $content)
    {

        $contentCount = count($content);

        if ($contentCount == 1) {
      
            return array_shift($content);
        }

        throw new MapFilter_TreePattern_NotExactlyOneFollowerException(
            'node_attr', $contentCount
        );
    }

    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Array|ArrayAccess             &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied or not.
     *
     * @since 0.5.3
     */
    public function satisfy(&$query, MapFilter_TreePattern_Asserts $asserts)
    {

        assert(MapFilter_TreePattern::isMap($query));

        $follower = self::_assertNonSingleFollower($this->getContent());
        
        if (!array_key_exists($this->_attribute, $query)) {

            $this->setAssertValue($asserts);
            return $this->satisfied = false;
        }

        $valueCandidate = self::convertIterator($query[ $this->_attribute ]);

        if (!is_array($valueCandidate)|| $valueCandidate === Array()) {
        
            $this->setAssertValue($asserts);
            return $this->satisfied = false;
        }

        $isIterator = $this->_iterator === self::ITERATOR_VALUE_YES;
        if ($isIterator) {
        
            $this->content = Array();
          
            foreach ($valueCandidate as $singleCandidate) {

                /** Scalar can not by satisfied. array expected */
                if(!MapFilter_TreePattern::isIterator($singleCandidate)) continue;
            
                $follower = clone $this->_follower;

                $satisfied = $follower->satisfy(
                    $singleCandidate,
                    $asserts
                );

                if ($satisfied) {

                    $this->content[] = $follower;
                    $this->satisfied = true;
                }
            }
        } else {

            $this->satisfied = $follower->satisfy(
                $valueCandidate,
                $asserts
            );
        } 

        if (!$this->satisfied) {

            $this->setAssertValue($asserts);
        }

        return $this->satisfied;
    }
    
    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing result.
     *
     * @return Array
     *
     * @since 0.5.3
     */
    public function pickUp(Array $result)
    {

        if (!$this->isSatisfied()) return Array();
      
        $isIterator = ($this->_iterator === self::ITERATOR_VALUE_YES);

        foreach ($this->getContent() as $follower) {

            $followerResult = $follower->pickUp(Array());
            
            if ($isIterator) {

                if ($followerResult === Array()) continue;
              
                $result[ $this->_attribute ][] = $followerResult;
            } else {

                $result[ $this->_attribute ] = $followerResult;
            }
        }
        
        return $result;
    }
}
