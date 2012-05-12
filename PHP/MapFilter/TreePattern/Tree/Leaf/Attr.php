<?php
/**
 * Attr Pattern leaf.
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
 * @since    0.4
 */

require_once 'PHP/MapFilter/TreePattern/Tree/Leaf.php';

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Leaf_Attr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
class MapFilter_TreePattern_Tree_Leaf_Attr extends
    MapFilter_TreePattern_Tree_Leaf
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

    /**
     * Satisfy certain node type and let its followers to get satisfied.
     *
     * @param Mixed &$query A query to filter.
     *
     * @return Bool Satisfied or not.
     *
     * Attr leaf is satisfied when its attribute occurs in user query and its
     * value matches the optional pattern defined by valuePattern attribute. 
     * When this does not happen this node still can be satisfied if its
     * default value is sat: attribute will have that default value and leaf
     * will be satisfied.
     *
     * @since 0.4
     */
    public function satisfy(&$query)
    {
    
        assert(MapFilter_TreePattern::isMap($query));

        $this->attribute->setQuery($query);

        $attributeAsserts = $this->_getAsserts($query);
        if (!$this->satisfied) {
        
            return MapFilter_TreePattern_Result::builder()
                ->putFlags($this->getFlags())
                ->putAsserts($attributeAsserts)
                ->build($this->satisfied)
            ;
        }

        $value = $this->attribute->getValue();

        $attrName = (String) $this->attribute;

        $this->satisfied = true;
        if (array_key_exists($attrName, $query) && is_array($value)) {

            $oldValue = self::convertIterator($query[ $attrName ]);

            $oldValue = (is_array($oldValue))
                ? $oldValue
                : Array()
            ;

            $setAsserts = (Bool) $assertValue = array_values(
                @array_diff($oldValue, $value)
            );

            if ($setAsserts && $this->validationAssert !== null) {

                $attributeAsserts->set(
                    $this->validationAssert, null, $assertValue
                );
            }
        }

        return MapFilter_TreePattern_Result::builder()
            ->putFlags($this->getFlags())
            ->putAsserts($attributeAsserts)
            ->build(true)
        ;
    }
    
    /**
     * Get element asserts
     *
     * @param Mixed $query A query to filter.
     *
     * @return MapFilter_TreePattern_Asserts
     *
     * @since $NEXT$
     */
    private function _getAsserts($query)
    {

        $this->satisfied = true;

        $asserts = new MapFilter_TreePattern_Asserts;

        if (!$this->attribute->isPresent()) {

            $this->satisfied = false;
            if ( $this->existenceAssert !== null) {
            
                return $asserts->set($this->existenceAssert);
            }
        } elseif (!$this->attribute->isValid()) {
        
            $this->satisfied = false;
            if ($this->validationAssert !== null) {

                return $asserts->set(
                    $this->validationAssert,
                    null,
                    $query[ (String) $this->attribute ]
                );
            }
        }
        
        return $asserts;
    }
}
