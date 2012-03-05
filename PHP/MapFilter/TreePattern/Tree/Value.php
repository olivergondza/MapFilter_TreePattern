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
 * @class    MapFilter_TreePattern_Tree_Value
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Value extends MapFilter_TreePattern_Tree
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
     * Value matcher
     *
     * @var MapFilter_TreePattern_Tree_Matcher  $pattern
     *
     * @since   $NEXT$
     */
    protected $pattern = null;
    
    /**
     * Value replacer
     *
     * @var MapFilter_TreePattern_Tree_Replacer $replacement
     *
     * @since   $NEXT$
     */
    protected $replacement = null;
    
    /**
     * Element default value
     *
     * @var String                              $default
     *
     * @since   $NEXT$
     */
    protected $default = null;
    
    private $_result;
    
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
    
        $this->pattern = $builder->pattern;
        $this->replacement = $builder->replacement;
        $this->default = $builder->default;
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
    
        assert(empty($result));
        
        if (!$this->isSatisfied()) return null;
        
        return $this->_result->getResults();
    }
    
    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Scalar                        &$query  A query to filter.
     * @param MapFilter_TreePattern_Asserts $asserts Asserts.
     *
     * @return Bool Satisfied or not.
     *
     * @since     $NEXT$
     */
    public function satisfy(&$query, MapFilter_TreePattern_Asserts $asserts)
    {

        $this->satisfied = $this->_isSatisfied($query);

        if (!$this->satisfied) {

            if (is_null($this->default)) {
        
                return $this->_result = $this->createResult($asserts, $query);
            }

            $query = $this->default;
        }

        $this->data = $this->_replace($query);

        $this->satisfied = true;
        if (array_key_exists(0, $this->content)) {
        
            $result = $this->content[ 0 ]->satisfy($this->data, $asserts);
            
            $this->satisfied = $result->isValid();

            return $this->_result = $this->createResult($asserts, $this->data)
                ->getBuilder()
                ->putResult($result)
                ->build($this->data, $this->satisfied)
            ;
        }

        return $this->_result = $this->createResult($asserts, $this->data);
    }
    
    /**
     * Deterine whether the element is satisfied
     *
     * @param Scalar $query Data.
     *
     * @return Bool Satisfied.
     *
     * @since     $NEXT$
     */
    private function _isSatisfied ($query)
    {

        if ($this->pattern === null) return true;

        if (!$this->_isString($query)) return false;

        return $this->pattern->match($query);
    }
    
    /**
     * Determion whether the value is string.
     *
     * @param Scalar $query Data.
     *
     * @return Bool String.
     *
     * @since     $NEXT$
     */
    private function _isString($query)
    {

        if (is_scalar($query)) return true;
      
        return (is_object($query) && method_exists($query, '__toString'));
    }
    
    /**
     * Perform replacement
     *
     * @param Scalar $query Data.
     *
     * @return String Replaced data;
     *
     * @since $NEXT$
     */
    private function _replace($query)
    {
    
        $cannotReplace = $this->replacement === null || !$this->_isString($query);
        if ($cannotReplace) return $query;
      
        return $this->replacement->replace((String) $query);
    }
}
