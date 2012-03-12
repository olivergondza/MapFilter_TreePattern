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

require_once 'PHP/MapFilter/TreePattern/Tree/Interface.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Matcher.php';

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
     * Name of the element
     *
     * @var     String                                    $elementName
     *
     * @since   $NEXT$
     */
    public $elementName = null;
    
    /**
     * Create new tree instance.
     *
     * @param MapFilter_TreePattern_Tree_Builder $builder A bulder to use.
     *
     * @return    MapFilter_TreePattern_Tree_Interface
     *
     * @since     0.3
     */
    public function __construct(MapFilter_TreePattern_Tree_Builder $builder)
    {

        $this->elementName = $builder->elementName;    
        $this->flag = $builder->flag;
        $this->existenceAssert = $builder->existenceAssert;
        $this->validationAssert = $builder->validationAssert;
        $this->attachPattern = $builder->attachPattern;
        $this->content = $builder->content;
    
        $this->_valueFilter = new MapFilter_TreePattern_Tree_Matcher(
            $builder->valueFilter
        );
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
    protected function &getContent()
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
     * Determine whether the node is satisfied.
     *
     * @return    Bool            Satisfied or not.
     *
     * @since     0.4
     */
    protected function isSatisfied()
    {

        assert(is_bool($this->satisfied));
    
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
            ? array_values(iterator_to_array($valueCandidate))
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
     * Get flags to set for current element
     *
     * @return MapFilter_TreePattern_Flags Flags
     *
     * @since $NEXT$
     */
    protected function getFlags ()
    {

        $flags = (!$this->isSatisfied() || $this->flag === null) 
            ? Array ()
            : Array ( $this->flag )
        ;

        return new MapFilter_TreePattern_Flags($flags);
    }
    
    /**
     * Get asserts to set for current element
     *
     * @param Mixed $useData Assert data to set.
     *
     * @return MapFilter_TreePattern_Asserts New asserts
     *
     * @since $NEXT$
     */
    protected function getAsserts($useData)
    {
    
        assert($this->validationAssert === $this->existenceAssert);

        if ($this->isSatisfied() || $this->validationAssert === null) {
      
            return new MapFilter_TreePattern_Asserts;
        }

        $asserts = new MapFilter_TreePattern_Asserts;

        return ($useData !== null)
            ? $asserts->set($this->validationAssert, null, $useData)
            : $asserts->set($this->validationAssert)
        ;
    }
    
    /**
     * Instantiate current result
     *
     * @param Mixed $useData Assert data to set.
     *
     * @return MapFilter_TreePattern_Result New result.
     *
     * @since $NEXT$
     */
    protected function createResult($useData = null)
    {
    
        return new MapFilter_TreePattern_Result(
            null,
            $this->getAsserts($useData),
            $this->getFlags(),
            $this->isSatisfied()
        );
    }
}
