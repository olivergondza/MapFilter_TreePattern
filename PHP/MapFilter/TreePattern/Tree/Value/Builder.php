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

require_once 'PHP/MapFilter/TreePattern/Tree/Value.php';

require_once 'PHP/MapFilter/TreePattern/NotExactlyOneFollowerException.php';
require_once 'PHP/MapFilter/TreePattern/Tree/InvalidContentException.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Value_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Value_Builder extends
    MapFilter_TreePattern_Tree_Builder
{

    /**
     * Value matcher
     *
     * @var MapFilter_TreePattern_Tree_Matcher  $pattern
     *
     * @since   $NEXT$
     */
    public $pattern = null;
    
    /**
     * Value replacer
     *
     * @var MapFilter_TreePattern_Tree_Replacer $replacement
     *
     * @since   $NEXT$
     */
    public $replacement = null;
    
    /**
     * Element default value
     *
     * @var String                              $default
     *
     * @since   $NEXT$
     */
    public $default = null;
    
    /**
     * Element folowers
     *
     * @var Array                               $content
     *
     * @since   $NEXT$
     */
    public $content = Array();

    /**
     * Set text content for the element
     *
     * @param String $name Attribute name.
     *
     * @return null
     * @throws MapFilter_TreePattern_Tree_InvalidContentException
     *
     * @since   $NEXT$
     */
    public function setTextContent($name)
    {
    
        throw new MapFilter_TreePattern_Tree_InvalidContentException(
            $this->elementName
        );
    }

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

        $follower = array_shift($content);
        if ( $follower instanceof MapFilter_TreePattern_Tree_Value
            || $follower instanceof MapFilter_TreePattern_Tree_Policy
            || $follower instanceof MapFilter_TreePattern_Tree_Leaf_AliasAttr
        ) {
        
            $this->content = Array($follower);
        }
    }

    /**
     * Set pattern
     *
     * @param String $pattern Pattern.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    public function setPattern($pattern)
    {
    
        $this->pattern = new MapFilter_TreePattern_Tree_Matcher($pattern);
    }
    
    /**
     * Set replacement
     *
     * @param String $replacement Replacement.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    public function setReplacement($replacement)
    {
    
        $this->replacement = new MapFilter_TreePattern_Tree_Replacer(
            $replacement
        );
    }
    
    /**
     * Set default value
     *
     * @param String $default Default.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    public function setDefault($default)
    {
    
        $this->default = $default;
    }

    /**
     * Build tree element
     *
     * @return MapFilter_TreePattern_Tree_Leaf_Attr Tree Element
     *
     * @return null
     *
     * @since    $NEXT$
     */
    public function build()
    {
    
        return new MapFilter_TreePattern_Tree_Value($this);
    }
}
