<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.10
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.10
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
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($since_id=null, $user=null, $include=array (), $limit=10) {
            if ($user) {
                $url = sprintf ('/users/%s/dashboard', $user);
            }
            else {
                $url = '/dashboard';
            }

            if (!is_null ($since_id) && $since_id) {
                $url .= sprintf ('/since/%s', $since_id);
            }

            $limit = (int)$limit;
            if ($limit) {
                $url .= '?limit='.$limit;
            }

            if ($include) {
                $url .= ($limit ? '&' : '?'). 'include=' . implode (',', $include);
            }

            return array ($url, 'get');
        }
    }
}

