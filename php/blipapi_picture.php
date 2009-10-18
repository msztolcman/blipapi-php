<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.15
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.15
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Picture')) {
    class BlipApi_Picture implements IBlipApi_Command {
        /**
        * Read picture attached to status/message/update
        *
        * Throws UnexpectedValueException when update ID is missing
        *
        * @param int $id picture ID
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param bool $since_id
        * @param int $limit default to 10
        * @param int $offset default to 0
        * @access public
        * @return array parameters for BlipApi::__query
        */

        public static function read ($id=null, $include=null, $since_id=false, $limit=10, $offset=0) {
            if ($id && $since_id) {
                $url = "/pictures/$id/all_since";
            }
            else if ($id) {
                $url = "/updates/$id/pictures";
            }
            else {
                $url = "/pictures/all";
            }

            $params = array ();

            if ($limit) {
                $params['limit'] = $limit;
            }

            if ($offset) {
                $params['offset'] = $offset;
            }

            if ($include) {
                $params['include'] = implode (',', $include);
            }

            if (count ($params)) {
                $url .= '?'.BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

