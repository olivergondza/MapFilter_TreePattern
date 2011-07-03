<?php
/**
 * Invalid pattern name exception.
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
 * Invalid pattern name exception.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_InvalidPatternNameException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class
    MapFilter_TreePattern_InvalidPatternNameException
extends
    UnexpectedValueException
{

    /**
     * Instantiate using default values
     *
     * @param String    $message  Exception message
     * @param Int       $code     Exception code
     * @param Exception $previous Previous exception
     */
    public function __construct (
        $message = "Pattern '%s' can not be attached.",
        $code = 0,
        Exception $previous = null
    ) {
    
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Set pattern name
     *
     * @param String $name Pattern name
     *
     * @return MapFilter_TreePattern_InvalidPatternElementException
     */
    public function setName($name)
    {
    
        assert(is_string($name));
      
        $this->message = sprintf($this->message, $name);
      
        return $this;
    }
}
