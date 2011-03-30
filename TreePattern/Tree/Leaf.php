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

require_once 'PHP/TreePattern/Tree.php';

require_once 'PHP/TreePattern/Tree/Leaf/Interface.php';

require_once 'PHP/TreePattern/Tree/Leaf/InvalidDepthIndicatorException.php';

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
     * @since     $NEXT$
     */
    public function __construct()
    {
    
        $this->attribute = new MapFilter_TreePattern_Tree_Attribute;

        $this->setSetters(
            Array(
                'attr' => 'setAttribute',
                'default' => 'setDefault',
                'existenceDefault' => 'setExistenceDefault',
                'validationDefault' => 'setValidationDefault',
                'valuePattern' => 'setValuePattern',
                'valueReplacement' => 'setValueReplacement',
                'existenceAssert' => 'setExistenceAssert',
                'validationAssert' => 'setValidationAssert',
                'iterator' => 'setIterator',
            )
        );

        parent::__construct();
    }
    
    /**
     * Set attribute.
     *
     * A Fluent Method.
     *
     * @param String $attribute An attribute to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new attribute.
     *
     * @since     0.4
     */
    public function setAttribute($attribute)
    {

        $this->attribute->setAttribute($attribute);
        return $this;
    }
    
    /**
     * Set default value.
     *
     * A Fluent Method.
     *
     * @param String $default A default value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new default value.
     *
     * @since     0.4
     */
    public function setDefault($default)
    {

        $this->attribute->setDefault($default);
        return $this;
    }
    
    /**
     * Set existence default value.
     *
     * A Fluent Method.
     *
     * @param String $existenceDefault A default value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new default value.
     *
     * @since     $NEXT$
     */
    public function setExistenceDefault($existenceDefault)
    {

        $this->attribute->setExistenceDefault($existenceDefault);
        return $this;
    }
    
    /**
     * Set validation default value.
     *
     * A Fluent Method.
     *
     * @param String $validationDefault A default value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new default value.
     *
     * @since     $NEXT$
     */
    public function setValidationDefault($validationDefault)
    {

        $this->attribute->setValidationDefault($validationDefault);
        return $this;
    }
    
    /**
     * Set valuePattern.
     *
     * A Fluent Method.
     *
     * @param String $valuePattern A valueFilter to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new valueFilter.
     *
     * @since     0.4
     */
    public function setValuePattern($valuePattern)
    {

        $this->attribute->setValuePattern($valuePattern);
        return $this;
    }
    
    /**
     * Set valueReplacement.
     *
     * A Fluent Method.
     *
     * @param String $valueReplacement A valueReplacement to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface
     *          A pattern with new valueReplacement.
     *
     * @since     $NEXT$
     */
    public function setValueReplacement($valueReplacement)
    {

        $this->attribute->setValueReplacement($valueReplacement);
        return $this;
    }
    
    /**
     * Set existenceAssert.
     *
     * @param String $existenceAssert An existenceAssert to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface New pattern with existenceAssert.
     *
     * @since     $NEXT$
     */
    public function setExistenceAssert($existenceAssert)
    {
    
        $this->existenceAssert = $existenceAssert;
        return $this;
    }
    
    /**
     * Set validationAssert.
     *
     * @param String $validationAssert A validationAssert to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface
     *          New pattern with validationAssert.
     *
     * @since     $NEXT$
     */
    public function setValidationAssert($validationAssert)
    {
    
        $this->validationAssert = $validationAssert;
        return $this;
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
     * Set iterator.
     *
     * A Fluent Method.
     *
     * @param String $iterator An iterator value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface New pattern with iterator.
     * @throws MapFilter_TreePattern_Tree_Leaf_InvalidDepthIndicatorException
     *
     * @since     0.5.2
     */
    public function setIterator($iterator)
    {

        assert(is_string($iterator) || is_int($iterator));

        $wordToLevel = Array (
            self::ITERATOR_VALUE_YES => 1,
            self::ITERATOR_VALUE_NO => 0,
        );
        
        if (array_key_exists($iterator, $wordToLevel)) {
        
            $iterator = $wordToLevel[ $iterator ];
        }

        if (!is_numeric($iterator)) {
        
            $ex = new MapFilter_TreePattern_Tree_Leaf_InvalidDepthIndicatorException;
            throw $ex->setValue($iterator);
        }

        $this->attribute->setIterator((Int) $iterator);

        return $this;
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
    public function pickUp(Array $result)
    {

        if (!$this->isSatisfied()) return Array ();

        $result[ $this->attribute->getAttribute() ]
            = $this->attribute->getValue()
        ;

        foreach ($this->getContent() as $follower) {

            $result = array_merge(
                $result,
                $follower->pickUp($result)
            );
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
