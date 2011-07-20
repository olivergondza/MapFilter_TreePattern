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

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Node/All.php';

require_once 'PHP/MapFilter/TreePattern/Xml/InvalidXmlContentException.php';

/**
 * Tree All element builder class
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_Attr_Builder
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Leaf_Attr_Builder extends
    MapFilter_TreePattern_Tree_Leaf_Builder
{

    /**
     * Set text content for the element
     *
     * @param String $name Attribute name.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    public function setTextContent($name)
    {
    
        $this->setAttr($name);
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
    
        throw new MapFilter_TreePattern_Xml_InvalidXmlContentException;
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
    
        return new MapFilter_TreePattern_Tree_Leaf_Attr($this);
    }
}
