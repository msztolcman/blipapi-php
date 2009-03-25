<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.13
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.13
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Tag')) {
    class BlipApi_Tag implements IBlipApi_Command {
        /**
        * Get updates for tag
        *
        * @param string $tag tag name
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param int $since_id status ID - will return statuses with newest ID then it
        * @param int $limit
        * @param int $offset
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($tag, $include=null, $since_id=null, $limit=10, $offset=0) {
            if (!$tag) {
                throw new UnexpectedValueException ('Tag name is missing.', -1);
            }

            $url = '/tags/'.$tag;

            if ($since_id) {
                $url .= '/since/' . (int)$since_id;
            }

            $params = array ();

            $limit = (int)$limit;
            if ($limit) {
                $params['limit'] = $limit;
            }
            $offset = (int)$offset;
            if ($offset) {
                $params['offset'] = $offset;
            }
            if ($include) {
                $params['include'] = implode (',', $include);
            }

            if (count ($params)) {
                $url .= '?' . BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

