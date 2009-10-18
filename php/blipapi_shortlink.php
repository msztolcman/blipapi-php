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

if (!class_exists ('BlipApi_Shortlink')) {
    class BlipApi_Shortlink implements IBlipApi_Command {
        /**
        * Get shortlinks from Blip!'s rdir system
        *
        * @param string $code
        * @param int $since_id status ID - will return statuses with newest ID then it
        * @param int $limit
        * @param int $offset
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($code=null, $since_id=null, $limit=10, $offset=0) {
            $url = '/shortlinks';
            if ($code) {
                $url .= "/$code";
            }
            else if ($since_id) {
                $url .= "/$since_id/all_since";
            }
            else {
                $url .= '/all';
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

            if (count ($params)) {
                $url .= '?'.BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

