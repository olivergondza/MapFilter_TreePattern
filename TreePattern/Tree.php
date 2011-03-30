<?php
/**
 * Abstract Pattern node; Ancestor of all pattern nodes.
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
 * @since    0.3
 */

require_once 'PHP/TreePattern/Tree/Interface.php';

require_once 'PHP/TreePattern/Tree/Matcher.php';

/**
 * Internal pattern tree.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.3
 */
abstract class MapFilter_TreePattern_Tree implements
    MapFilter_TreePattern_Tree_Interface
{

    /**
     * Tree pattern reference.
     *
     * @since     0.5.3
     *
     * @var       MapFilter_TreePattern_Interface         $_pattern
     */
    private $_pattern = null;
    
    /**
     * Determine whether the node was already satisfied.
     *
     * @since     0.3
     *
     * @var       Bool                                    $satisfied
     */
    protected $satisfied = false;
    
    /**
     * Key-Attr value filter.
     *
     * @since     0.3
     *
     * @var       MapFilter_TreePattern_Tree_Matcher      $_valueFilter
     */
    private $_valueFilter = null;
    
    /**
     * Node flag.
     *
     * @since     0.3
     *
     * @var       String                                  $flag
     */
    protected $flag = null;
    
    /**
     * Node assert.
     *
     * @since     $NEXT$
     *
     * @var       String                                  $existenceAssert
     */
    protected $existenceAssert = null;
    
    /**
     * Node assert.
     *
     * @since     $NEXT$
     *
     * @var       String                                  $validationAssert
     */
    protected $validationAssert = null;
    
    /**
     * Node content.
     *
     * @since     0.5.2
     *
     * @var       Array                                   $content
     */
    protected $content = Array();
    
    /**
     * Attache pattern.
     *
     * @since     0.5.3
     *
     * @var       String                                  $attachPattern
     */
    protected $attachPattern = null;
    
    /**
     * Node setters
     *
     * @since     $NEXT$
     *
     * @var       Array                                   $setters
     */
    protected $setters = Array();
    
    /**
     * Create new tree instance.
     *
     * @since     0.3
     *
     * Setting is done by Fluent Methods.
     *
     * @return    MapFilter_TreePattern_Tree_Interface
     */
    public function __construct()
    {
    
        $this->setSetters(
            Array(
                'flag' => 'setFlag',
                'assert' => 'setAssert',
                'attachPattern' => 'setAttachPattern',
                'forValue' => 'setValueFilter',
            )
        );
      
        $this->_valueFilter = new MapFilter_TreePattern_Tree_Matcher;
    }
    
    /**
     * Set setter methods alloved for node.
     *
     * @param Array $setters Array of attrs and its setters.
     *
     * @return  MapFilter_TreePattern_Tree_Interface
     *
     * @since     $NEXT$
     */
    protected function setSetters(Array $setters)
    {
    
        $this->setters += $setters;
        return $this;
    }
    
    /**
     * Get alloved setters.
     *
     * @since     $NEXT$
     *
     * @return    Array
     */
    public function getSetters()
    {
    
        return $this->setters;
    }
    
    /**
     * Set Flag.
     *
     * @param String $flag A flag to set.
     *
     * @return    MapFilter_TreePattern_Tree_Interface    New pattern with flag.
     *
     * @since     0.4
     */
    public function setFlag($flag)
    {
    
        assert(is_string($flag));
    
        $this->flag = $flag;
        return $this;
    }
    
    /**
     * Set Assert.
     *
     * @param String $assert An assert to set.
     *
     * @return    MapFilter_TreePattern_Tree_Interface    New pattern with flag.
     *
     * @since     0.4
     */
    public function setAssert($assert)
    {
    
        assert(is_string($assert));
    
        $this->existenceAssert = $this->validationAssert = $assert;
        return $this;
    }
    
    /**
     * Set attachPattern.
     *
     * @param String $attachPattern A pattern name to attach.
     *
     * @return MapFilter_TreePattern_Tree_Interface New pattern with attachPattern.
     *
     * @since     0.5.3
     */
    public function setAttachPattern($attachPattern)
    {
    
        assert(is_string($attachPattern));
      
        $this->attachPattern = $attachPattern;
        return $this;
    }

    /**
     * Set valueFilter.
     *
     * @param String $valueFilter A valueFilter to set.
     *
     * @return MapFilter_TreePattern_Tree_Interface New pattern with valueFilter.
     *
     * @since     0.4
     */
    public function setValueFilter($valueFilter)
    {

        assert(is_string($valueFilter));

        $this->_valueFilter = new MapFilter_TreePattern_Tree_Matcher(
            $valueFilter
        );

        return $this;
    }
    
    /**
     * Get valueFilter.
     *
     * @return MapFilter_TreePattern_Tree_Matcher Node value filter.
     *
     * @since     0.3
     */
    protected function getValueFilter()
    {
    
        return $this->_valueFilter;
    }
    
    /**
     * Get node followers reference.
     *
     * @return    Array           Node content reference.
     *
     * @since     0.3
     */
    public function &getContent()
    {
    
        $this->attachPattern();
    
        return $this->content;
    }
    
    /**
     * Set TreePattern.
     *
     * @param MapFilter_TreePattern $pattern A pattern to set.
     *
     * @return    MapFilter_TreePattern
     *
     * @since     0.5.3
     */
    public function setTreePattern(MapFilter_TreePattern $pattern)
    {
    
        $this->_pattern = $pattern;
      
        foreach ($this->content as $follower) {
      
            $follower->setTreePattern($pattern);
        }
      
        return $this;
    }
    
    /**
     * Set assertion value.
     *
     * @param MapFilter_TreePattern_Asserts $asserts     Existing asserts.
     * @param Mixed                         $assertValue An assert value to set.
     *
     * @return  null
     *
     * @since      0.5.2
     */
    protected function setAssertValue(
        MapFilter_TreePattern_Asserts $asserts, $assertValue = Array()
    ) {
    
        if ($assertValue === Array()) {

             $name = $this->existenceAssert;
             $value = null;
        } else {

             $name = $this->validationAssert;
             $value = $assertValue;
        }
        
        if ($name === null) return;
          
        $asserts->set($name, null, $value);
    }
    
    /**
     * Determine whether the node is satisfied.
     *
     * @return    Bool            Satisfied or not.
     *
     * @since     0.4
     */
    protected function isSatisfied()
    {
    
        return $this->satisfied;
    }
    
    /**
     * Actually attach a side pattern if needed.
     *
     * @return    null
     *
     * @since     0.5.3
     */
    protected function attachPattern()
    {
    
        if ($this->attachPattern === null) return;
      
        if ($this->content !== Array()) return;

        $this->content = Array(
            $this->_pattern->getSidePattern($this->attachPattern)
        );
    }
    
    /**
     * Convert iterator to an array.
     *
     * @param Mixed $valueCandidate A value to convert.
     * 
     * @return            Mixed
     *
     * @since             0.5.2
     */
    protected function convertIterator($valueCandidate)
    {

        return ($valueCandidate instanceof Iterator)
            ? iterator_to_array($valueCandidate, false)
            : $valueCandidate
        ;
    }
    
    /**
     * Clone node followers.
     *
     * @return    MapFilter_TreePattern_Tree
     *
     * @note This method uses deep cloning.
     *
     * @since     0.3
     */
    public function __clone()
    {
    
        foreach ($this->content as &$follower) {

            $follower = clone $follower;
        }
    }
    
    /**
     * Get filtering flags.
     *
     * @param MapFilter_TreePattern_Flags $flags Existing flags.
     *
     * @return  null
     *
     * @since     0.5.1
     */
    public function pickUpFlags(MapFilter_TreePattern_Flags $flags)
    {
    
        if (!$this->isSatisfied()) return;
        if ($this->flag !== null) {
        
            $flags->set($this->flag);
        }
        
        foreach ($this->getContent() as $follower) {

            $follower->pickUpFlags($flags);
        }
    }
}
