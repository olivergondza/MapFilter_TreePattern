<?php
/**
 * Pattern replacer.
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

require_once 'PHP/MapFilter/TreePattern/Tree/Replacer/InvalidStructureException.php';

/**
 * Pattern Replacer.
 *
 * @category Pear
 * @package  MapFilter_TreePattern
 * @class    MapFilter_TreePattern_Tree_Replacer
 * @author   Oliver Gondza <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Replacer
{

    /**
     * Replacement RegExp
     *
     * @since     $NEXT$
     */
    const REPLACEMENT_REGEXP =
        '/^s?
          (?P<search>
            ([^\\\\\s\da-zA-Z<>\{\}\(\)]) # Possible delimiters
            (?:(?!(?<!\\\\)\2).)*         # Delimiter has to be escaped
            (\2)
          )
          (?P<replace>
            (?:(?!(?<!\\\\)\2).)*         # Delimiter has to be escaped
          )
          (\2)
          (?P<modifiers>[imsxeADSUXu]*)
        $/x'
    ;

    /**
     * Search RegExp.
     *
     * @since     $NEXT$
     *
     * @var       String          $_pattern
     */
    private $_pattern = null;
    
    /**
     * Replacement.
     *
     * @since     $NEXT$
     *
     * @var       String          $_replacement
     */
    private $_replacement = null;
    
    /**
     * Create replacer
     *
     * @param String $searchAndReplace Initializer string.
     *
     * @throws MapFilter_TreePattern_Tree_Replacer_InvalidStructureException
     *
     * @since     $NEXT$
     */
    public function __construct($searchAndReplace = null)
    {
    
        assert(is_string($searchAndReplace) || is_null($searchAndReplace));
        
        if ($searchAndReplace === null) return;
        
        $matches = Array();
        $valid = preg_match(
            self::REPLACEMENT_REGEXP, $searchAndReplace, $matches
        );

        if (!$valid) {
        
            $ex = new MapFilter_TreePattern_Tree_Replacer_InvalidStructureException;
            throw $ex->setInput($searchAndReplace);
        }
        
        $this->_pattern = $matches[ 'search' ] . $matches[ 'modifiers' ];
        $this->_replacement = str_replace('\/', '/', $matches[ 'replace' ]);
    }
    
    /**
     * Replace if there is any pattern.
     *
     * @param String $subject A subject of replacement.
     *
     * @return    String
     */
    public function replace($subject)
    {
    
        if ($this->_pattern === null) return $subject;
        
        assert(is_string($subject));
        
        return preg_replace($this->_pattern, $this->_replacement, $subject);
    }
}
