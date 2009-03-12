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

if (!class_exists ('BlipApi_Notices')) {
    class BlipApi_Notices implements IBlipApi_Command {
        /**
        * Get last notices for user
        *
        * @param int $since_id status ID - will return notices with newest ID then it
        * @param int $limit
        * @param int $offset
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($since_id=null, $limit=10, $offset=0) {
            $url = '/notices';

            if ($since_id) {
                $url .= '/since/' . $since_id;
            }

            $limit = (int)$limit;
            if ($limit) {
                $url .= '?limit='.$limit;
            }

            $offset = (int)$offset;
            if ($offset) {
                $url .= ($limit ? '&' : '?') . 'offset=' . $offset;
            }

            return array ($url, 'get');
        }
    }
}

