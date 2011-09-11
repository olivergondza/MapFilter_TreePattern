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
 * @since    0.5.3
 */

require_once 'PHP/MapFilter/TreePattern/Tree/Node/All/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Node/Opt/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Node/One/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Node/Some/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Node/NodeAttr/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/KeyAttr/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/AliasAttr/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Leaf/Attr/Builder.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Value/Builder.php';

require_once 'PHP/MapFilter/TreePattern/Tree/Key/Builder.php';
require_once 'PHP/MapFilter/TreePattern/Tree/Iterator/Builder.php';

require_once 'PHP/MapFilter/TreePattern/Xml/LibXmlException.php';
require_once 'PHP/MapFilter/TreePattern/InvalidPatternElementException.php';
require_once 'PHP/MapFilter/TreePattern/NotExactlyOneFollowerException.php';
require_once 'PHP/MapFilter/TreePattern/ColidingPatternNamesException.php';
require_once 'PHP/MapFilter/TreePattern/NoPatternSpecifiedException.php';
require_once 'PHP/MapFilter/TreePattern/NoMainPatternException.php';

/**
 * Class to load  Pattern tree.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Xml
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.3
 */
class MapFilter_TreePattern_Xml
{

    /**
     * Data is url.
     *
     * @since     0.1
     *
     * Load data from file
     */
    const DATA_IS_URL = true;
    
    /**
     * Data is string.
     *
     * @since     0.1
     *
     * Load data from string
     */
    const DATA_IS_STRING = false;
    
    /** @cond     INTERNAL */

    /**
     * Valid XML structure tag.
     * @{
     */
    const PATTERN = 'pattern';
    const PATTERNS = 'patterns';
    
    const NODE_ALL = 'all';
    const NODE_ONE = 'one';
    const NODE_OPT = 'opt';
    const NODE_SOME = 'some';
    const NODE_KEYATTR = 'key_attr';
    const NODE_ATTR = 'attr';
    const NODE_NODEATTR = 'node_attr';
    const NODE_ALIAS = 'alias';
    const NODE_VALUE = 'value';
    const NODE_KEY = 'key';
    const NODE_ITERATOR = 'iterator';
    /**@}*/
    
    /**
     * Node name Object type mapping.
     *
     * @since     0.4
     *
     * @var       Array           $_tagToNode
     */
    private static $_tagToNode = Array(
        self::NODE_ALL => 'MapFilter_TreePattern_Tree_Node_All_Builder',
        self::NODE_ONE => 'MapFilter_TreePattern_Tree_Node_One_Builder',
        self::NODE_OPT => 'MapFilter_TreePattern_Tree_Node_Opt_Builder',
        self::NODE_SOME => 'MapFilter_TreePattern_Tree_Node_Some_Builder',
        self::NODE_NODEATTR => 'MapFilter_TreePattern_Tree_Node_NodeAttr_Builder',
        self::NODE_KEYATTR => 'MapFilter_TreePattern_Tree_Leaf_KeyAttr_Builder',
        self::NODE_ATTR => 'MapFilter_TreePattern_Tree_Leaf_Attr_Builder',
        self::NODE_ALIAS => 'MapFilter_TreePattern_Tree_Leaf_AliasAttr_Builder',
        self::NODE_VALUE => 'MapFilter_TreePattern_Tree_Value_Builder',
        self::NODE_KEY => 'MapFilter_TreePattern_Tree_Key_Builder',
        self::NODE_ITERATOR => 'MapFilter_TreePattern_Tree_Iterator_Builder',
    );
    
    /**
     * Valid XML attribute.
     */
    const ATTR_NAME = 'name';
    
    /**
     * Array key for attributes
     *
     * @since     0.5.3
     */
    const ATTR_ARRAY_KEY = '@attributes';
    
    /** @endcond */
    
    private $_xmlElement = null;
    private $_mainTreeElement = null;
    private $_sideTreeElements = Array();
    
    /**
     * Simple Factory Method to load data from string.
     *
     * @param String $xmlSource Pattern string.
     *
     * @return    MapFilter_TreePattern   Pattern created from $xmlSource string
     *
     * @see       fromFile()
     *
     * @since     $NEXT$
     */
    public static function load($xmlSource)
    {
      
        assert(is_string($xmlSource));
        
        return self::_build(
            self::_loadXml($xmlSource, self::DATA_IS_STRING)
        );
    }

    /**
     * Simple Factory Method to instantiate with loading the data from file.
     *
     * @param String $url XML pattern file.
     *
     * @return    MapFilter_TreePattern   Pattern created from $url file
     * 
     * @see       load()
     *
     * @since     $NEXT$
     */
    public static function fromFile($url)
    {
    
        assert(is_string($url));
      
        return self::_build(
            self::_loadXml($url, self::DATA_IS_URL)
        );
    }
    
    /**
     * Load Xml source and create XmlElement.
     *
     * @param String $xml   XML source.
     * @param Bool   $isUrl URL or String.
     *
     * @return    SimpleXmlElement                XmlElement of input.
     * @throws    MapFilter_TreePattern_Xml_LibXmlException
     *
     * @since     0.1
     */
    private static function _loadXml($xml, $isUrl)
    {
    
        assert(is_string($xml));
        assert(is_bool($isUrl));
      
        /** Suppress Error | Warning vomiting into the output stream */
        libxml_use_internal_errors(true);
        
        /**
         * Options used for XML deserialization by SimpleXmlElement
         * Use compact data allocation | remove blank nodes | translate HTML entities
         */
        $options = LIBXML_COMPACT & LIBXML_NOBLANKS & LIBXML_NOENT;

        /** Try to load and raise proper exception accordingly */
        try {
        
            $xmlElement = new SimpleXmlElement($xml, $options, $isUrl);
        } catch (Exception $exception) {

            $error = libxml_get_last_error();
            libxml_clear_errors();

            if ($error === false) throw $exception;
          
            throw MapFilter_TreePattern_Xml_LibXmlException::wrap($error);
        }

        return $xmlElement;
    }
    
    /**
     * Build pattern from xmlElement
     *
     * @param SimpleXmlElement $xmlElement An input element
     *
     * @return MapFilter_TreePattern
     *
     * @since $NEXT$
     */
    private static function _build(SimpleXmlElement $xmlElement)
    {
    
        $parser = new self($xmlElement);
        return $parser->_parse();
    }
    
    /**
     * Instantiate Xml deserializer
     *
     * @param SimpleXmlElement $xmlElement An input element
     *
     * @return MapFilter_TreePattern_Xml
     *
     * @since $NEXT$
     */
    private function __construct(SimpleXmlElement $xmlElement)
    {
    
        $this->_xmlElement = $xmlElement;
    }
    
    /**
     * Create Pattern instance out of xmlEleemnt
     *
     * @return MapFilter_TreePattern
     *
     * @since $NEXT$
     */
    private function _parse ()
    {

        $this->_unwrap();

        $sideTrees = array_map(
            Array($this, '_parseTree'),
            $this->_sideTreeElements
        );

        $mainTree = $this->_parseTree($this->_mainTreeElement);
        
        return new MapFilter_TreePattern($mainTree, $sideTrees);
    }
    
    /**
     * Identify main and side patterns in the input
     *
     * @return    SimpleXmlElement   Unwrapped element.
     * @throws    MapFilter_TreePattern_NotExactlyOneFollowerException
     *            MapFilter_TreePattern_InvalidPatternElementException
     *
     * @since     0.4
     */
    private function _unwrap()
    {
     
        $tagName = $this->_xmlElement->getName();
       
        /** Tree is not wrapped */
        if ($this->_isValidTag($tagName)) {
        
            $this->_mainTreeElement = $this->_xmlElement;
        } elseif ($tagName === self::PATTERN) {
        
            $this->_unwrapPattern();
        } elseif ($tagName === self::PATTERNS) {
        
            $this->_unwrapMultiplePatterns();
        } else {

            throw new MapFilter_TreePattern_InvalidPatternElementException(
                $tagName
            );
        }
        
        $this->_xmlElement = null;
    }
    
    /**
     * Unwrap single pattern
     *
     * @since   $NEXT$
     *
     * @return  null
     */
    private function _unwrapPattern()
    {
    
        $count = (Int) count($this->_xmlElement->children());
        if ( $count !== 1) {
        
            throw new MapFilter_TreePattern_NotExactlyOneFollowerException(
                self::PATTERN, $count
            );
        }
        
        $name = $this->_getElementName($this->_xmlElement);
        if ($name!==MapFilter_TreePattern::MAIN_PATTERN) {
        
            throw new MapFilter_TreePattern_NoMainPatternException;
        }
        
        $this->_mainTreeElement = $this->_xmlElement->children();
    }
    
    /**
     * Unwrap multiple patterns
     *
     * @since  $NEXT$
     *
     * @return  null
     */
    private function _unwrapMultiplePatterns()
    {
    
        if (count($this->_xmlElement->children()) < 1) {
        
            throw new MapFilter_TreePattern_NoPatternSpecifiedException;
        }
      
        $this->_sideTreeElements = Array();
        foreach ($this->_xmlElement->children() as $child) {
          
            $count = (Int) count($child->children());
            if ($count !== 1) {

                throw new MapFilter_TreePattern_NotExactlyOneFollowerException(
                    self::PATTERN, $count
                );
            }

            $this->_store($child);
        }
        
        if (is_null($this->_mainTreeElement)) {
        
            throw new MapFilter_TreePattern_NoMainPatternException;
        }
    }
    
    /**
     * Get value of element name attribute
     *
     * @param SimpleXmlElement $child A child to examine.
     *
     * @return String|Null Element name
     *
     * @since   $NEXT$
     */
    private function _getElementName (SimpleXmlElement $child)
    {
    
        $attributes = $this->_getAttributes($child);
    
        return (array_key_exists(self::ATTR_NAME, $attributes))
            ? $attributes[ self::ATTR_NAME ]
            : MapFilter_TreePattern::MAIN_PATTERN
        ;
    }
    
    /**
     * Store pattern
     *
     * @param SimpleXmlElement $child A pattern to store.
     *
     * @return null
     *
     * @since   $NEXT$
     */
    private function _store (SimpleXmlElement $child)
    {
    
        $name = $this->_getElementName($child);
    
        if ($name === MapFilter_TreePattern::MAIN_PATTERN) {
        
            if (is_null($this->_mainTreeElement)) {

                $this->_mainTreeElement = $child->children();
                return;
            }
        } elseif (!array_key_exists($name, $this->_sideTreeElements)) {
        
            $this->_sideTreeElements[ $name ] = $child->children();
            return;
        }
        
        throw new MapFilter_TreePattern_ColidingPatternNamesException($name);
    }
    
    /**
     * Parse serialized pattern tree to its object implementation.
     *
     * @param SimpleXmlElement $xml An element to parse.
     *
     * @return    MapFilter_TreePattern_Tree      Parsed pattern.
     * @throws    MapFilter_TreePattern_MissingAttributeValueException
     *
     * @since     0.4
     */
    private function _parseTree(SimpleXmlElement $xml)
    {

        $tagName = $this->_validateTagName($xml);

        /** Parse followers recursively */
        $followers = Array ();
        foreach ( $xml->children() as $child ) {
        
            $followers[] = $this->_parseTree($child);
        }

        $node = $this->_createNodeBuilder($tagName, $followers);

        $node = $this->_parseTagAttributes($xml, $node, $tagName);
        
        $textContent = trim((String) $xml[ 0 ]);
        if (!empty($textContent)) {

            $node->setTextContent($textContent);
        }

        return $node->build();
    }

    /**
     * Get tag name.
     *
     * @param SimpleXmlElement $xml A node to validate.
     *
     * @return    String                  New tag name.
     *
     * @throws    MapFilter_InvalidPatternElementException
     *
     * @since     0.4
     */
    private function _validateTagName(SimpleXmlElement $xml)
    {

        /**
         * $tagName = $xml->getName (); can not be used.
         * Yealds parent name instead in PHP 5.2.3
         */
        $tagName = current($xml->xpath('.'))->getName();

        if ($this->_isValidTag($tagName)) return $tagName;

        throw new MapFilter_TreePattern_InvalidPatternElementException(
            $tagName
        );
    }
    
    /**
     * Determines whether a tag is valid.
     *
     * @param String $tag A tag name to test.
     *
     * @return    Bool            Valid or not
     *
     * @since     0.4
     */
    private function _isValidTag($tag)
    {
    
        assert(is_string($tag));
    
        return array_key_exists($tag, self::$_tagToNode);
    }
    
    /**
     * Create the node according to tag name.
     *
     * @param String $tagName   A tag name.
     * @param Array  $followers Set of followers to use as a content.
     *
     * @return    MapFilter_TreePattern_Tree
     * @throws    MapFilter_TreePattern_Xml_InvalidXmlContent
     *
     * @since     0.4
     */
    private function _createNodeBuilder($tagName, Array $followers)
    {
    
        assert(is_string($tagName));
      
        /** Instantiate pattern node */
        $class = self::$_tagToNode[ $tagName ];
        $node = new $class($tagName);

        if ($followers === Array()) return $node;

        $node->setContent($followers);

        return $node;
    }
    
    /**
     * Parse attributes of existing tag.
     *
     * @param SimpleXmlEmement           $xml         A node to parse.
     * @param MapFilter_TreePattern_Tree $nodeBuilder A pattern node to fill.
     * @param String                     $tagName     A name of tag.
     *
     * @return    MapFilter_TreePattern_Tree      A pattern node with attributes.
     *
     * @since     0.4
     */
    private function _parseTagAttributes(
        SimpleXmlElement $xml,
        MapFilter_TreePattern_Tree_Builder $nodeBuilder,
        $tagName
    ) {
    
        assert(is_string($tagName));
      
        $attributes = $this->_getAttributes($xml);
        foreach ($attributes as $attrName => $attrVal) {
        
            $method = MapFilter_TreePattern_Tree_Builder::getSetter($attrName);
            $nodeBuilder->$method($attrVal);
        }

        return $nodeBuilder;
    }
    
    /**
     * Get Element attributes
     *
     * @param SimpleXmlElement $element An element to examine
     *
     * @return Array
     */
    private function _getAttributes(SimpleXmlElement $element)
    {
    
        $attrs = (Array) $element->attributes();
        return array_key_exists(self::ATTR_ARRAY_KEY, $attrs)
            ? $attrs[ self::ATTR_ARRAY_KEY ]
            : Array()
        ;
    }
}
