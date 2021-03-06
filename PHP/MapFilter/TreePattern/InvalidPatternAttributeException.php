<?php
/**
 * Missing attribute exception.
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
 * Missing attribute exception.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_InvalidPatternAttributeException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class
    MapFilter_TreePattern_InvalidPatternAttributeException
extends
    UnexpectedValueException
{

    /**
     * Instantiatate using default values.
     *
     * @param String $node      Node name
     * @param String $attribute Attribute name
     * @param String $message   Exception message
     *
     * @since    $NEXT$
     */
    public function __construct (
        $node, $attribute, $message = "Node '%s' has no attribute like '%s'."
    ) {
    
        assert(is_string($node));
        assert(is_string($attribute));
        assert(is_string($message));
    
        parent::__construct(sprintf($message, $node, $attribute));
    }
}
