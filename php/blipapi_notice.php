<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.16
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.16
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Notice')) {
    class BlipApi_Notice implements IBlipApi_Command {
        /**
        * Get last notices for user
        *
        * @param int $id status ID
        * @param string $user username
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param int $since_id status ID - will return notices with newest ID then it
        * @param int $limit
        * @param int $offset
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($id=null, $user=null, $include=null, $since_id=null, $limit=10, $offset=0) {
            $url = '/notices';

            if ($user) {
                if ($user == '__all__') {
                    if ($id) {
                        $url .= "/$id";
                    }
                    $url .= "/all";
                    if ($since_id) {
                        $url .= '_since';
                    }
                }
                else {
                    $url = "/users/$user/notices";
                    if ($since_id && $id) {
                        $url .= "/$id/since";
                    }
                }
            }
            else {
                if ($id && $since_id) {
                    $url .= "/since/$id";
                }
                else if ($id) {
                    $url .= "/$id";
                }
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

