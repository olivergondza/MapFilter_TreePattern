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

require_once 'PHP/MapFilter/TreePattern/Tree.php';

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Key
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Key extends MapFilter_TreePattern_Tree
{

    /**
     * Element content
     *
     * @var mixed                               $data
     *
     * @since   $NEXT$
     */
    protected $data = null;
    
    /**
     * Key name
     *
     * @var String                              $name
     *
     * @since   $NEXT$
     */
    private $_name = null;
    
    /**
     * Instantiate attribute
     *
     * @param MapFilter_TreePattern_Tree_Builder $builder A builder to use.
     *
     * @since     $NEXT$
     */
    public function __construct(MapFilter_TreePattern_Tree_Builder $builder)
    {

        parent::__construct($builder);
        
        $this->_name = $builder->name;
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

        if ($result === null && $this->data instanceof ArrayAccess) {
        
            $result = new ArrayObject ();
        }

        $result[$this->_name] = $this->data[$this->_name];

        return $result;
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

        $this->satisfied = $this->_isSatisfied($query, $asserts);
        
        if (!$this->satisfied) {
        
            if ($this->_isPresent($query)) {

                $this->setAssertValue($asserts, $query[ $this->_name ]);
            } else {
            
                $this->setAssertValue($asserts);
            }
            return false;
        }
        
        $this->data = $query;
        return true;
    }
    
    /**
     * Determine whether the key is present in the query
     *
     * @param Mixed $query A query to examine.
     *
     * @return Bool Is present.
     *
     * @since $NEXT$
     */
    private function _isPresent ($query)
    {
    
        if (!MapFilter_TreePattern::isMap($query)) return false;
        
        return is_array($query)
            ? array_key_exists($this->_name, $query)
            : $query->offsetExists($this->_name)
        ;
    }
    
    /**
     * Determine whether the lement is satisfied
     *
     * @param Mixed                         &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied or not.
     *
     * @since     $NEXT$
     */
    private function _isSatisfied(
        &$query, MapFilter_TreePattern_Asserts $asserts
    ) {
    
        if (!MapFilter_TreePattern::isMap($query)) return false;
        
        $valid = $this->_isPresent($query);

        $followers = $this->getContent();
        if (empty($followers)) return $valid;
        
        if (!$valid) {
        
            $query[ $this->_name ] = null;
        }
        
        $followerSatisfied = $this->content[ 0 ]->satisfy(
            $query[ $this->_name ], $asserts
        );
        
        if ($valid && $followerSatisfied) return true;
        
        return $followerSatisfied && $query[ $this->_name ] !== null;
    }
}
