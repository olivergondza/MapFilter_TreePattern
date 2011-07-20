<?php
/**
 * Class to load Pattern tree from xml.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Leaf.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
abstract class MapFilter_TreePattern_Tree_Leaf_Builder extends
    MapFilter_TreePattern_Tree_Builder
{

    /**
     * Element name
     *
     * @var String $name
     *
     * @since    $NEXT$
     */
    public $name = null;
    
    /**
     * Iterator
     *
     * @var String $iterator
     *
     * @since    $NEXT$
     */
    public $iterator = 0;
    
    /**
     * Existence default
     *
     * @var String $existenceDefault
     *
     * @since    $NEXT$
     */
    public $existenceDefault = null;
    
    /**
     * Validation default
     *
     * @var String $validationDefault
     *
     * @since    $NEXT$
     */
    public $validationDefault = null;
    
    /**
     * Value patern
     *
     * @var String $valuePattern
     *
     * @since    $NEXT$
     */
    public $valuePattern = null;
    
    /**
     * Value replacement
     *
     * @var String $valueReplacement
     *
     * @since    $NEXT$
     */
    public $valueReplacement = null;

    /**
     * Set iterator.
     *
     * @param String $depth An iterator value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface New pattern with iterator.
     * @throws MapFilter_TreePattern_Tree_Leaf_InvalidDepthIndicatorException
     *
     * @since     0.5.2
     */
    public function setIterator($depth)
    {
    
        assert(is_string($depth) || is_int($depth));

        $wordToLevel = Array (
            MapFilter_TreePattern_Tree_Leaf::ITERATOR_VALUE_YES => 1,
            MapFilter_TreePattern_Tree_Leaf::ITERATOR_VALUE_NO => 0,
        );
        
        if (array_key_exists($depth, $wordToLevel)) {
        
            $depth = $wordToLevel[ $depth ];
        }

        if (!is_numeric($depth)) {
        
            $ex = new MapFilter_TreePattern_Tree_Leaf_InvalidDepthIndicatorException;
            throw $ex->setValue($depth);
        }
    
        $this->iterator = (Int) $depth;
    }
    
    /**
     * Set default value.
     *
     * @param String $value A default value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new default value.
     *
     * @since     0.4
     */
    public function setDefault($value)
    {
    
        $this->setExistenceDefault($value);
        $this->setValidationDefault($value);
    }
    
    /**
     * Set existence default value.
     *
     * @param String $value A default value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new default value.
     *
     * @since     $NEXT$
     */
    public function setExistenceDefault($value)
    {
    
        $this->existenceDefault = $value;
    }
    
    /**
     * Set validation default value.
     *
     * @param String $value A default value to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new default value.
     *
     * @since     $NEXT$
     */
    public function setValidationDefault($value)
    {
    
        $this->validationDefault = $value;
    }
    
    /**
     * Set valuePattern.
     *
     * @param String $pattern A valueFilter to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new valueFilter.
     *
     * @since     0.4
     */
    public function setValuePattern($pattern)
    {
    
        $this->valuePattern = $pattern;
    }
    
    /**
     * Set valueReplacement.
     *
     * @param String $replacement A valueReplacement to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface
     *          A pattern with new valueReplacement.
     *
     * @since     $NEXT$
     */
    public function setValueReplacement($replacement)
    {
    
        $this->valueReplacement = $replacement;
    }
    
    /**
     * Set existenceAssert.
     *
     * @param String $name An existenceAssert to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface New pattern with existenceAssert.
     *
     * @since     $NEXT$
     */
    public function setExistenceAssert($name)
    {
    
        $this->existenceAssert = $name;
    }
    
    /**
     * Set validationAssert.
     *
     * @param String $name A validationAssert to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface
     *          New pattern with validationAssert.
     *
     * @since     $NEXT$
     */
    public function setValidationAssert($name)
    {
    
        $this->validationAssert = $name;
    }
    
    /**
     * Set attribute.
     *
     * @param String $name An attribute to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface A pattern with new attribute.
     *
     * @since     0.4
     */
    public function setAttr($name)
    {
    
        $this->name = $name;
    }
}
