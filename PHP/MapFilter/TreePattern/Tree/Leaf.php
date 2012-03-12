<?php
/**
 * Pattern Leaf.
 *
 * PHP Version 5.1.0
 *
 * This file is part of MapFilter package.
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

require_once 'PHP/MapFilter/TreePattern/Tree.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/Interface.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/InvalidDepthIndicatorException.php';

/**
 * Abstract class for pattern tree leaf.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
abstract class
    MapFilter_TreePattern_Tree_Leaf
extends
    MapFilter_TreePattern_Tree
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

    /**
     * Node attribute.
     *
     * @since     0.4
     *
     * @var       MapFilter_TreePattern_Tree_Attribute          $attribute
     */
    protected $attribute = '';
    
    /**
     * Instantiate attribute
     *
     * @param MapFilter_TreePattern_Tree_Builder $builder A builder to use.
     *
     * @since     $NEXT$
     */
    public function __construct(MapFilter_TreePattern_Tree_Builder $builder)
    {
    
        $this->attribute = new MapFilter_TreePattern_Tree_Attribute;
        $this->attribute->setAttribute($builder->name);
        $this->attribute->setExistenceDefault($builder->existenceDefault);
        $this->attribute->setValidationDefault($builder->validationDefault);
        $this->attribute->setValuePattern($builder->valuePattern);
        $this->attribute->setValueReplacement($builder->valueReplacement);
        $this->attribute->setIterator($builder->iterator);
        $this->existenceAssert = $builder->existenceAssert;
        $this->validationAssert = $builder->validationAssert;
        
        parent::__construct($builder);
    }
    
    /**
     * Get node attribute.
     *
     * @return    String          A node attribute.
     * @see       setAttribute()
     *
     * @since     0.4
     */
    public function getAttribute()
    {
    
        return $this->attribute->getAttribute();
    }

    /**
     * Pick-up satisfaction results.
     *
     * @param Array $result Existing results
     *
     * @return    Array
     *
     * @since     0.3
     */
    public function pickUp($result)
    {

        if (!$this->isSatisfied()) return null;

        $attrName = $this->attribute->getAttribute();
        $result[ $attrName ] = $this->attribute->getValue();

        foreach ($this->getContent() as $follower) {

            $data = $follower->pickUp($result);
            
            if ( $data === null ) continue;

            foreach ( $data as $key => $val ) {

                $result[ $key ] = $val;
            }
        }

        return $result;
    }

    /**
     * Clone node followers.
     * 
     * Overwrite MapFilter_Pattern_Tree deep cloning method
     *
     * @return    MapFilter_TreePattern_Tree_Leaf
     *
     * @since     0.3
     */
    public function __clone()
    {
    
        $this->attribute = clone $this->attribute;
    }
}
