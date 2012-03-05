<?php
/**
 * Filtering asserts.
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

require_once 'PHP/MapFilter/TreePattern/Asserts/InvalidInitializationException.php';
require_once 'PHP/MapFilter/TreePattern/Asserts/MissingPropertyException.php';

/**
 * Filtering asserts.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Asserts
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Asserts
{

    /**
     * Assertion path.
     *
     * @since     $NEXT$
     */
    const PATH = 0;
    
    /**
     * Assertion value.
     *
     * @since     $NEXT$
     */
    const VALUE = 1;

    /**
     * Set asserts.
     *
     * @since     $NEXT$
     *
     * @var       Array           $_asserts
     */
    private $_asserts = Array();
    
    /**
     *
     */
    public static function create($assertName, &$value = null)
    {

        return new self(Array($assertName => $value));
    }
    
    /**
     * Create asserts
     *
     * @param Array $asserts Asserts
     *
     * @since     $NEXT$
     */
    public function __construct(Array $asserts = Array())
    {
    
        foreach ($asserts as $name => $details) {
        
            if (is_int($name) && !is_string($details)) {
          
                throw new MapFilter_TreePattern_Asserts_InvalidInitializationException;
            }
        
            $key =(!is_int($name))
                ? $name
                : $details
            ;
        
            $this->_asserts[ $key ] =(is_array($details))
                ? $details
                : Array()
            ;
        }
        
        ksort($this->_asserts);
    }

    /**
     * Set validationAssert including path and value
     *
     * @param String $assertName Assert identifier
     * @param String $path       Path
     * @param Mixed  &$value     Value reference
     *
     * @return    MapFilter_TreePattern_Asserts
     *
     * @since     $NEXT$
     */
    public function set($assertName, $path = null, &$value = null)
    {
    
        assert(is_string($assertName));
        assert(is_string($path) || is_null($path));

        if ($this->_isRedundant($assertName, $path, $value)) return $this;

        $this->_asserts[ $assertName ] = Array();
        
        if ($path !== null) {
        
            $this->_asserts[ $assertName ][ self::PATH ] = $path;
        }
        
        if ($value !== null) {
        
            $this->_asserts[ $assertName ][ self::VALUE ] = $value;
        }
        
        ksort($this->_asserts);
        
        return $this;
    }
    
    /**
     *
     */
    private function _isRedundant($assertName, $path, $value)
    {
    
        if (!$this->exists($assertName)) return false;
        
        $entry = $this->_asserts[ $assertName ];
        
        if (array_key_exists(self::PATH, $entry) && $path !== null) return false;
        if (array_key_exists(self::VALUE, $entry) && $value !== null) return false;
        
        return true;
    }
    
    /**
     * Get all asserts
     *
     * @return    Array
     *
     * @since     $NEXT$
     */
    public function getAll()
    {

        return array_keys($this->_asserts);
    }
    
    /**
     * Determine whether an assert is set
     *
     * @param String $assertName Assert identifier
     *
     * @return    Bool
     *
     * @since     $NEXT$
     */
    public function exists($assertName)
    {
    
        assert(is_string($assertName));
    
        return array_key_exists($assertName, $this->_asserts);
    }
    
    /**
     * Get assert path
     *
     * @param String $assertName Assert identifier
     * 
     * @return    String
     *
     * @since     $NEXT$
     */
    public function getPath($assertName)
    {
    
        return $this->_getAssertEntry($assertName, self::PATH, 'path');
    }
    
    /**
     * Get assert value
     *
     * @param String $assertName Assert identifier
     *
     * @return    &Mixed
     *
     * @since     $NEXT$
     */
    public function &getValue($assertName)
    {
    
        return $this->_getAssertEntry($assertName, self::VALUE, 'value');
    }
    
    /**
     * Get entry property if available
     *
     * @param String $assertName Assection name
     * @param String $attr       Attribute accessed
     * @param String $name       Symolic name for attribute
     *
     * @return    &Mixed
     *
     * @since     $NEXT$
     */
    private function &_getAssertEntry($assertName, $attr, $name)
    {
    
        assert(is_string($assertName));
        assert(is_string($name));
        
        if ($this->exists($assertName)) {
 
            if (array_key_exists($attr, $this->_asserts[ $assertName ])) {
            
                return $this->_asserts[ $assertName ][ $attr ];
            }
        }
        
        throw new MapFilter_TreePattern_Asserts_MissingPropertyException(
            $name, $assertName
        );
    }
    
    /**
     * Combibe asserts with several other assert sets
     *
     * @param Array $assertSets Assert sets to combine
     *
     * @return MapFilter_TreePattern_Asserts
     *
     * @since     $NEXT$
     */
    public function combine(Array $assertSets) {
    
        $result = clone $this;
        foreach ($assertSets as $asserts) {
        
            assert($asserts instanceof MapFilter_TreePattern_Asserts);
        
        
            $result->_asserts = array_merge(
                $result->_asserts, $asserts->_asserts
            );
        }
        
        return $result;
    }
    
    /**
     *
     */
    public function getMap () {
    
        $simpleMap = Array();
        foreach ( $this->getAll () as $name ) {
        
            try {

                $simpleMap[ $name ] = $this->getValue($name);
            } catch ( MapFilter_TreePattern_Asserts_MissingPropertyException $ex ) {
            
                $simpleMap[] = $name;
            }
        }
        
        return $simpleMap;
    }
}
