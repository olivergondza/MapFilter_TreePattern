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
require_once 'PHP/MapFilter/TreePattern/Tree/Iterator.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Iterator/InvalidLengthConstraintException.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Iterator/EmptyIntervalSpecifiedException.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Iterator_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Iterator_Builder extends
    MapFilter_TreePattern_Tree_Builder
{

    /**
     * Element folowers
     *
     * @var Array $content
     *
     * @since $NEXT$
     */
    public $content = Array();
    
    /**
     * Lower boundary
     *
     * @var Int $lowerBoundary
     *
     * @since $NEXT$
     */
    public $lowerBoundary = 0;
    
    /**
     * Upper boundary
     *
     * @var Int $upperBoundary
     *
     * @since $NEXT$
     */
    public $upperBoundary = null;
    
    /**
     * Set element content
     *
     * @param Array $content Content to set.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    public function setContent(Array $content)
    {
    
        $count = count($content);
        if ($count !== 1) {
        
            throw new MapFilter_TreePattern_NotExactlyOneFollowerException(
                $this->elementName, $count
            );
        }

        $this->content = $content;
    }
    
    /**
     * Set lower boundary
     *
     * @param Int $lowerBoundary Lower bondary
     *
     * @return null
     *
     * @since $NEXT$
     */
    public function setMin($lowerBoundary)
    {
    
        if (!preg_match('/^\d+$/', $lowerBoundary)) {
        
            throw new MapFilter_TreePattern_Tree_Iterator_InvalidLengthConstraintException(
                $lowerBoundary
            );
        }
        
        $this->lowerBoundary = (Int)$lowerBoundary;
    }
    
    /**
     * Set upper boundary
     *
     * @param Int $upperBoundary Upper bondary
     *
     * @return null
     *
     * @since $NEXT$
     */
    public function setMax($upperBoundary)
    {
    
        if (!preg_match('/^\d+$/', $upperBoundary)) {
        
            throw new MapFilter_TreePattern_Tree_Iterator_InvalidLengthConstraintException(
                $upperBoundary
            );
        }
        
        $this->upperBoundary = (Int)$upperBoundary;
    }

    /**
     * Build tree element
     *
     * @return MapFilter_TreePattern_Tree_Key Tree Element
     *
     * @since    $NEXT$
     */
    public function build()
    {
    
        $hasTopLimit = is_int($this->upperBoundary);
        $empty = $hasTopLimit && $this->lowerBoundary > $this->upperBoundary;
    
        if ($empty) {
        
            throw new MapFilter_TreePattern_Tree_Iterator_EmptyIntervalSpecifiedException(
                $this->lowerBoundary, $this->upperBoundary
            );
        }

        return new MapFilter_TreePattern_Tree_Iterator($this);
    }
}
