<?php
/**
 * Pattern Attribute.
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
 * @author   Oliver Gondza <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */

require_once 'PHP/MapFilter/TreePattern/Tree/Replacer.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Matcher.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Attribute/MissingValueException.php';

/**
 * Pattern attribute.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Attribute
 * @author   Oliver Gondza <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Attribute
{

    /**
     * Node attribute.
     *
     * @since     $NEXT$
     *
     * @var       String          $_attribute
     */
    private $_attribute = null;
    
    /**
     * Attr existenceDefault value.
     *
     * @since     $NEXT$
     *
     * @var       String          $_existenceDefault
     */
    private $_existenceDefault = null;
    
    /**
     * Attr validationDefault value.
     *
     * @since     $NEXT$
     *
     * @var       String          $_validationDefault
     */
    private $_validationDefault = null;
    
    /**
     * Attr valuePattern.
     *
     * @since     $NEXT$
     *
     * @var       MapFilter_TreePattern_Tree_Matcher      $_valuePattern
     */
    private $_valuePattern = null;
    
    /**
     * Attr valueReplacement.
     *
     * @since     $NEXT$
     *
     * @var       MapFilter_TreePattern_Tree_Replacer     $_valueReplacement
     */
    private $_valueReplacement = null;
    
    /**
     * Attr existenceAssert value.
     *
     * @since     $NEXT$
     *
     * @var       String          $_existenceAssert
     */
    private $_existenceAssert = null;
    
    /**
     * Attr validationAssert value.
     *
     * @since     $NEXT$
     *
     * @var       String          $_validationAssert
     */
    private $_validationAssert = null;

    /**
     * Determine whether a value is scalar or an array/iterator.
     *
     * @since     $NEXT$
     *
     * @var       String          $_iterator
     */
    private $_iterator = 0;

    /**
     * User query to examinate.
     *
     * @since     $NEXT$
     *
     * @var       Array|ArrayAccess       $_query
     */
    private $_query = Array();
    
    /**
     * Attribute value.
     *
     * @since     $NEXT$
     *
     * @var       String          $_value
     */
    private $_value = null;

    /**
     * Create attribute instance
     *
     * @since    $NEXT$
     */
    public function __construct()
    {
    
        $this->_valuePattern = new MapFilter_TreePattern_Tree_Matcher;
        $this->_valueReplacement = new MapFilter_TreePattern_Tree_Replacer;
    }

    /**
     * Set attribute.
     *
     * @param String $attribute An attribute to set.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     * @see       getAttribute
     *
     * @since     $NEXT$
     */
    public function setAttribute($attribute)
    {

        if (!$attribute) {
        
            throw new MapFilter_TreePattern_Tree_Attribute_MissingValueException;
        }

        $this->_attribute = $attribute;
        return $this;
    }
    
    /**
     * Get node attribute.
     *
     * @return    String          A node attribute.
     * @see       setAttribute
     *
     * @since     $NEXT$
     */
    public function getAttribute()
    {
    
        return $this->_attribute;
    }
    
    /**
     * Set validation default value.
     *
     * @param String $validationDefault A default value to set.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     * @see       setDefault, setExistenceDefault
     *
     * @since     $NEXT$
     */
    public function setValidationDefault($validationDefault)
    {

        $this->_validationDefault = $validationDefault;
        return $this;
    }
    
    /**
     * Set existence default value.
     *
     * @param String $existenceDefault A default value to set.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     * @see       setDefault, setValidationDefault
     *
     * @since     $NEXT$
     */
    public function setExistenceDefault($existenceDefault)
    {

        $this->_existenceDefault = $existenceDefault;
        return $this;
    }

    /**
     * Set valuePattern.
     *
     * @param String $valuePattern A valueFilter to set.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     *
     * @since     $NEXT$
     */
    public function setValuePattern($valuePattern)
    {

        $this->_valuePattern = new MapFilter_TreePattern_Tree_Matcher(
            $valuePattern
        );

        return $this;
    }
    
    /**
     * Set valueReplacement.
     *
     * @param String $valueReplacement A valueReplacement to set.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     *
     * @since     $NEXT$
     */
    public function setValueReplacement($valueReplacement)
    {

        $this->_valueReplacement = new MapFilter_TreePattern_Tree_Replacer (
            $valueReplacement
        );
      
        return $this;
    }
    
    /**
     * Set iterator.
     *
     * @param Int $iterator An iterator value to set.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     *
     * @since     $NEXT$
     */
    public function setIterator($iterator)
    {

        $this->_iterator = $iterator;
        return $this;
    }

    /**
     * Set iterator.
     *
     * @param Array|ArrayAccess $query User query.
     *
     * @return    MapFilter_TreePattern_Tree_Attribute
     *
     * @since     $NEXT$
     */
    public function setQuery($query)
    {
    
        assert(MapFilter_TreePattern::isMap($query));

        $this->_value = null;
        $this->_query = $query;
        return $this;
    }

    /**
     * Get attribute value.
     *
     * @return    Mixed
     *
     * @since     $NEXT$
     */
    public function getValue()
    {
    
        return $this->_value;
    }

    /**
     * Determine whether an attribute is present in query.
     *
     * @return    Bool
     *
     * @see       isValid
     *
     * @since     $NEXT$
     */
    public function isPresent()
    {
    
        $present = array_key_exists(
            $this->_attribute,
            $this->_query
        );

        if (!$present && $this->_existenceDefault !== null) {
          
            $this->_value = $this->_existenceDefault;
            for ($a = 0; $a < $this->_iterator; $a++) {
            
                $this->_value = Array($this->_value);
            }

            $this->_query[ $this->_attribute ] = $this->_value;
            $present = true;
        }

        return $present;
    }
    
    /**
     * Determine whether a value is valid.
     *
     * @return    Bool
     *
     * @see       isPresent
     *
     * @since     $NEXT$
     */
    public function isValid()
    {
    
        if (!$this->isPresent()) return false;

        $this->_value = $this->_query[ $this->_attribute ];
      
        $valid = $this->_validate(
            $this->_value
        );
        
        if (!$valid && $this->_validationDefault !== null) {
          
            $this->_value = $this->_validationDefault;
            for ($a = 0; $a < $this->_iterator; $a++) {
            
                $this->_value = Array($this->_value);
            }

            $this->_query[ $this->_attribute ] = $this->_value;
            $valid = true;
        }
        
        return $valid;
    }

    /**
     * Validate arbitrary iterator structure
     *
     * @param Mixed &$valueCandidate A value to validate
     * @param Int   $level           Nesting level
     *
     * @return    Bool
     *
     * @since     $NEXT$
     */
    private function _validate(&$valueCandidate, $level = 0)
    {

        assert(is_int($level));

        assert($level <= $this->_iterator);

        if ($level === $this->_iterator) {
      
            $valid = $this->_validateScalarValue($valueCandidate);
          
            if (!$valid && $this->_validationDefault !== null) {
          
                $valueCandidate = $this->_validationDefault;
                $valid = true;
            }
          
            return $valid;
        }
        
        if (!MapFilter_TreePattern::isIterator($valueCandidate)) return false;

        $values = Array();
        foreach ($valueCandidate as $singleValueCandidate) {
        
            if ($this->_validate($singleValueCandidate, $level + 1)) {
          
                $values[] = $singleValueCandidate;
            }
        }

        $valueCandidate = $values;
        
        return($values!==Array());
    }

    /**
     * Determine whether the value is valid or not.
     *
     * @param Mixed &$valueCandidate A value to validate
     *
     * @return            Bool           Valid or not.
     *
     * @since             0.5.2
     */
    private function _validateScalarValue(&$valueCandidate)
    {

        if (MapFilter_TreePattern::isIterator($valueCandidate)) return false;

        $fits = $this->_valuePattern->match((String)$valueCandidate);
        
        if (!$fits) return false;
        
        if ($this->_valueReplacement !== null) {
          
            $valueCandidate = $this->_valueReplacement->replace(
                $valueCandidate
            );
        }
        
        return true;
    }

    /**
     * Convert attribute to string.
     *
     * @since     $NEXT$
     *
     * @return    String          String representation of node.
     */
    public function __toString()
    {
    
        return (String) $this->_attribute;
    }
}
