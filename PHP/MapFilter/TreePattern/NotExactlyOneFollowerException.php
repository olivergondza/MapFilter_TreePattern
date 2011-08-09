<?php
/**
 * Not exactly one follower
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
 * Not exactly one follower
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_NotExactlyOneFollowerException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class
    MapFilter_TreePattern_NotExactlyOneFollowerException
extends
    UnexpectedValueException
{

    /**
     * Instantiate using default values
     *
     * @param String $node    Node name to set
     * @param String $count   Follower count
     * @param String $message Exception message
     *
     * @since $NEXT$
     */
    public function __construct(
        $node,
        $count,
        $message = "The '%s' node must have exactly one follower but %d given."
    ) {
    
        assert(is_string($node));
        assert(is_int($count));
        assert(is_string($message));
    
        parent::__construct(sprintf($message, $node, $count));
    }
}
