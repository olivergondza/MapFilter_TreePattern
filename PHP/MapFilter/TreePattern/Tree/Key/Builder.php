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

require_once 'PHP/MapFilter/TreePattern/Tree/Key.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Key_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Key_Builder extends
    MapFilter_TreePattern_Tree_Builder
{

    /**
     * Element folowers
     *
     * @var Array                               $content
     *
     * @since   $NEXT$
     */
    public $content = Array();
    
    /**
     * Key name
     *
     * @var String                              $name
     *
     * @since   $NEXT$
     */
    public $name = null;

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
     * Set key name
     *
     * @param String $name Key name.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    public function setName($name)
    {
    
        assert(is_string($name));
    
        $this->name = $name;
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
    
        return new MapFilter_TreePattern_Tree_Key($this);
    }
}
