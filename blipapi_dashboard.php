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

if (!class_exists ('BlipApi_Dashboard')) {
    class BlipApi_Dashboard implements IBlipApi_Command {
        /**
        * Return user current dashboard
        *
        * @param int $since_id status ID - will return statuses with newest ID then it
        * @param string $user
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param int $limit default to 10
        * @param int $offset default to 0
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($since_id=null, $user=null, $include=array (), $limit=10, $offset=0) {
            if ($user) {
                $url = "/users/$user/dashboard";
            }
            else {
                $url = '/dashboard';
            }

            if (!is_null ($since_id) && $since_id) {
                $url .= '/since/' . $since_id;
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
                $url .= '?'.BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

