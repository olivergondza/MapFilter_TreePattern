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

/** @cond       PROGRAMMER */

require_once 'PHP/TreePattern/Tree/Node/All.php';
require_once 'PHP/TreePattern/Tree/Node/Opt.php';
require_once 'PHP/TreePattern/Tree/Node/One.php';
require_once 'PHP/TreePattern/Tree/Node/Some.php';
require_once 'PHP/TreePattern/Tree/Node/NodeAttr.php';
require_once 'PHP/TreePattern/Tree/Leaf/KeyAttr.php';
require_once 'PHP/TreePattern/Tree/Leaf/AliasAttr.php';
require_once 'PHP/TreePattern/Tree/Leaf/Attr.php';

require_once 'PHP/TreePattern/InvalidPatternElementException.php';
require_once 'PHP/TreePattern/NotExactlyOneFollowerException.php';
require_once 'PHP/TreePattern/InvalidPatternAttributeException.php';
require_once 'PHP/TreePattern/MissingAttributeValueException.php';

require_once 'PHP/TreePattern/Xml/LibXmlException.php';
require_once 'PHP/TreePattern/Xml/InvalidXmlContentException.php';


/** @endcond */

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
        
        $xmlElement = self::loadXml(
            $xmlSource,
            self::DATA_IS_STRING
        );

        $sideTrees = self::unwrap($xmlElement);
        $sideTrees = array_map(
            'MapFilter_TreePattern_Xml::parseTree',
            $sideTrees
        );

        $xmlElement = self::parseTree($xmlElement);
        return new MapFilter_TreePattern($xmlElement, $sideTrees);
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
      
        $xmlElement = self::loadXml(
            $url,
            self::DATA_IS_URL
        );
        
        $sideTrees = self::unwrap($xmlElement);
        $sideTrees = array_map(
            'MapFilter_TreePattern_Xml::parseTree',
            $sideTrees
        );

        $xmlElement = self::parseTree($xmlElement);
        return new MapFilter_TreePattern($xmlElement, $sideTrees);
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
    public static function loadXml($xml, $isUrl)
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
    const NODE_KEYATTR = 'key_attr';
    const NODE_ATTR = 'attr';
    const NODE_SOME = 'some';
    const NODE_NODEATTR = 'node_attr';
    const NODE_ALIAS = 'alias';
    /**@}*/
    
    /**
     * Node name Object type mapping.
     *
     * @since     0.4
     *
     * @var       Array           $_tagToNode
     */
    private static $_tagToNode = Array(
        self::NODE_ALL => 'MapFilter_TreePattern_Tree_Node_All',
        self::NODE_ONE => 'MapFilter_TreePattern_Tree_Node_One',
        self::NODE_OPT => 'MapFilter_TreePattern_Tree_Node_Opt',
        self::NODE_SOME => 'MapFilter_TreePattern_Tree_Node_Some',
        self::NODE_NODEATTR => 'MapFilter_TreePattern_Tree_Node_NodeAttr',
        self::NODE_KEYATTR => 'MapFilter_TreePattern_Tree_Leaf_KeyAttr',
        self::NODE_ATTR => 'MapFilter_TreePattern_Tree_Leaf_Attr',
        self::NODE_ALIAS => 'MapFilter_TreePattern_Tree_Leaf_AliasAttr',
    );
    
    /**
     * Valid XML attribute.
     */
    const ATTR_NAME = 'name';
    
    /** @endcond */
    
    /**
     * Determines whether a tag is valid.
     *
     * @param String $tag A tag name to test.
     *
     * @return    Bool            Valid or not
     *
     * @since     0.4
     */
    private static function _isValidTag($tag)
    {
    
        assert(is_string($tag));
    
        return array_key_exists($tag, self::$_tagToNode);
    }
    
    /**
     * Throw when there are some leftover attributes.
     *
     * @param String $tagName    A tag with attributes.
     * @param Array  $attributes Leftover attributes.
     *
     * @return  null
     *
     * @throws    MapFilter_TreePattern_InvalidPatternAttributeException
     *
     * @since     0.4
     */
    private static function _assertLeftoverAttrs($tagName, Array $attributes)
    {
    
        assert(is_string($tagName));
    
        if ($attributes != Array()) {
        
            $attrs = array_keys($attributes);

            $ex = new MapFilter_TreePattern_InvalidPatternAttributeException;
            throw $ex->setNodeAndAttribute($tagName, $attrs[ 0 ]);
        }
    }
    
    /**
     * Obtain and remove attribute from an array of attributes.
     *
     * @param Array  &$attributes Array of all provided attributes.
     * @param String $attribute   Attribute to obtain.
     *
     * @return    String|false            Attribute name or false.
     *
     * @since     0.4
     */
    private static function _getAttribute(Array &$attributes, $attribute)
    {
    
        assert(is_string($attribute));
    
        if (!array_key_exists($attribute, $attributes)) return false;

        /** Fetch and delete */
        $value = (String) $attributes[ $attribute ];
        unset ($attributes[ $attribute]);
    
        return $value;
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
    private static function _validateTagName(SimpleXmlElement $xml)
    {
    
        $tagName = $xml->getName();

        if (self::_isValidTag($tagName)) return $tagName;

        $ex = new MapFilter_TreePattern_InvalidPatternElementException;
        throw $ex->setName($tagName);
    }

    /**
     * Array key for attributes
     *
     * @since     0.5.3
     */
    const ATTR_ARRAY_KEY = '@attributes';
    
    /**
     * Parse attributes of existing tag.
     *
     * @param SimpleXmlEmement           $xml     A node to parse.
     * @param MapFilter_TreePattern_Tree $node    A pattern node to fill.
     * @param String                     $tagName A name of tag.
     *
     * @return    MapFilter_TreePattern_Tree      A pattern node with attributes.
     *
     * @since     0.4
     */
    private static function _parseTagAttributes(
        SimpleXmlElement $xml,
        MapFilter_TreePattern_Tree $node,
        $tagName
    ) {
    
        assert(is_string($tagName));
      
        /** Obtain all attributes and set them using a bunch of soft setters */
        $attrs = (Array) $xml->attributes();
        $attributes = array_key_exists(self::ATTR_ARRAY_KEY, $attrs)
            ? $attrs[self::ATTR_ARRAY_KEY]
            : Array()
        ;
        
        foreach ($node->getSetters() as $attr => $setter) {

            /** Reset loop if attribute does not exist */
            $attrValue = self::_getAttribute($attributes, $attr);

            if ($attrValue === false) continue;

            $node = call_user_func(Array($node, $setter), $attrValue);
        }

        /** Unset attributes and make sure that none of them left over */
        self::_assertLeftoverAttrs($tagName, $attributes);

        return $node;
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
    private static function _createTreeNode($tagName, Array $followers)
    {
    
        assert(is_string($tagName));
      
        /** Instantiate pattern node */
        $class = self::$_tagToNode[ $tagName ];
        $node = new $class();

        if ($followers === Array()) return $node;

        if (!array_key_exists('content', $node->getSetters())) {

            $ex = new MapFilter_TreePattern_Xml_InvalidXmlContentException;
            throw $ex->setNodeName($tagName);
        }
        
        $node->setContent($followers);

        return $node;
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
    public static function parseTree(SimpleXmlElement $xml)
    {

        $tagName = self::_validateTagName($xml);

        /** Parse followers recursively */
        $followers = array_map(
            'self::parseTree',
            iterator_to_array($xml->children(), false)
        );

        $node = self::_createTreeNode($tagName, $followers);

        $node = self::_parseTagAttributes($xml, $node, $tagName);

        /** Attr node can have attribute in tag body so special check is needed. */
        if (get_class($node) === 'MapFilter_TreePattern_Tree_Leaf_Attr') {

            $alreadySet =(Bool)($node->getAttribute());

            $available =(Bool) strlen((String) $xml[ 0 ]);

            if (!$alreadySet && !$available) {

                throw new MapFilter_TreePattern_MissingAttributeValueException;
            }

            if ($available) {

                $node->setAttribute(trim((String) $xml[ 0 ]));
            }
        }

        return $node;
    }
    
    /**
     * Unwrap not necessary \<pattern\> tags from very beginning and end of tree.
     *
     * @param SimpleXmlElement &$xmlElement An element to unwrap.
     *
     * @return    SimpleXmlElement   Unwrapped element.
     * @throws    MapFilter_TreePattern_NotExactlyOneFollowerException
     *            MapFilter_TreePattern_InvalidPatternElementException
     *
     * @since     0.4
     */
    public static function unwrap(SimpleXmlElement &$xmlElement)
    {
     
        $tagName = $xmlElement->getName();
       
        /** Tree is not wrapped */
        if (self::_isValidTag($tagName)) return Array();

        /** Unwrap pattern tag */
        if ($tagName === self::PATTERN) {
        
            if ($xmlElement->count() !== 1) {
            
                $ex = new MapFilter_TreePattern_NotExactlyOneFollowerException;
                throw $ex->setNodeAndCount(
                    self::PATTERN, (Int) $xmlElement->count()
                );
            }

            $xmlElement = $xmlElement->children();

            return Array();
        }

        /** Unwrap patterns tag */
        if ($tagName === self::PATTERNS) {
        
            if ($xmlElement->count() < 1) {
            
                $ex = new MapFilter_TreePattern_NotExactlyOneFollowerException;
                throw $ex->setNodeAndCount(self::PATTERN, 0);
            }
          
            $sidePatterns = Array();
            foreach (iterator_to_array($xmlElement, false) as $child) {
              
                if ($child->count() !== 1) {

                    $ex = new MapFilter_TreePattern_NotExactlyOneFollowerException;
                    throw $ex->setNodeAndCount(
                        self::PATTERN, (Int) $child->count()
                    );
                }

                $attrs =(Array) $child->attributes();

                $name = (
                    array_key_exists(self::ATTR_ARRAY_KEY, $attrs)
                    && array_key_exists(
                        self::ATTR_NAME, $attrs[ self::ATTR_ARRAY_KEY ]
                    )
                 )
                    ? $attrs[self::ATTR_ARRAY_KEY][ self::ATTR_NAME ]
                    : false
                ;

                $isMain = $name === false
                    || $name === MapFilter_TreePattern::MAIN_PATTERN
                ;

                if ( $isMain ) {
                
                    $xmlElement = $child->children();
                } else {
                
                    $sidePatterns[ $name ] = $child->children();
                }
            }
            
            return $sidePatterns;
        }

        /** Unknown tag */
        $ex = new MapFilter_TreePattern_InvalidPatternElementException;
        throw $ex->setName($tagName);
    }
}
