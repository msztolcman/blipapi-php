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

if (!class_exists ('BlipApi_Shortlink')) {
    class BlipApi_Shortlink implements IBlipApi_Command {
        /**
        * Get shortlinks from Blip!'s rdir system
        *
        * @param int $since_id status ID - will return statuses with newest ID then it
        * @param int $limit
        * @param int $offset
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($since_id=null, $limit=10, $offset=0) {
            if ($since_id) {
                $url = "/shortlinks/$since_id/all_since";
            }
            else {
                $url = '/shortlinks/all';
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

