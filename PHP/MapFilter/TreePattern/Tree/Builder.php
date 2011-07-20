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

/**
 * Tree element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
abstract class MapFilter_TreePattern_Tree_Builder
{
    
    /**
     * Validation assert
     *
     * @var String $validationAssert
     *
     * @since    $NEXT$
     */
    public $validationAssert = null;
        
    /**
     * Existence assert
     *
     * @var String $existenceAssert
     *
     * @since    $NEXT$
     */
    public $existenceAssert = null;    
    
    /**
     * Flag
     *
     * @var Array $flag
     *
     * @since    $NEXT$
     */
    public $flag = null;
        
    /**
     * Value filter
     *
     * @var Array $valueFilter
     *
     * @since    $NEXT$
     */
    public $valueFilter = null;
    
    /**
     * Attach pattern
     *
     * @var Array $attachPattern
     *
     * @since    $NEXT$
     */
    public $attachPattern = null;
    
    /**
     * Content
     *
     * @var Array $content
     *
     * @since    $NEXT$
     */
    public $content = Array ();

    /**
     * Create builder
     *
     * @since    $NEXT$
     */
    final public function __construct()
    {
    }
    
    /**
     * Set Assert.
     *
     * @param String $assert An assert to set.
     *
     * @return null
     *
     * @since     0.4
     */
    public function setAssert($assert)
    {
    
        $this->validationAssert = $this->existenceAssert = $assert;
    }
    
    /**
     * Set Flag.
     *
     * @param String $flag A flag to set.
     *
     * @return null
     *
     * @since 0.4
     */
    public function setFlag($flag)
    {
    
        $this->flag = $flag;
    }
    
    /**
     * Set valueFilter.
     *
     * @param String $valueFilter A valueFilter to set.
     *
     * @return null
     *
     * @since     0.4
     */
    public function setForValue($valueFilter)
    {
    
        $this->valueFilter = $valueFilter;
    }
    
    /**
     * Set attachPattern.
     *
     * @param String $patternName A pattern name to attach.
     *
     * @return null
     *
     * @since     0.5.3
     */
    public function setAttachPattern($patternName)
    {
    
        $this->attachPattern = $patternName;
    }
    
    /**
     * Set content.
     *
     * @param Array $followers A content to set.
     *
     * @return null
     *
     * @since     0.3
     */
    public function setContent(Array $followers)
    {
    
        $this->content = $followers;
    }
    
    /**
     * Build tree element
     *
     * @return MapFilter_TreePattern_Tree Tree Element
     *
     * @return null
     *
     * @since    $NEXT$
     */
    abstract public function build();
    
    /**
     * Prohibit all the other setters
     *
     * @param String $name Setter name
     * @param Array  $args Some args
     *
     * @return null
     * @throws MapFilter_TreePattern_InvalidPatternAttributeException
     */
    public function __call ( $name, Array $args )
    {
    
        throw new MapFilter_TreePattern_InvalidPatternAttributeException;
    }
}
