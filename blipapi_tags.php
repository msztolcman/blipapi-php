<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.11
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.11
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Tags')) {
    class BlipApi_Tags implements IBlipApi_Command {
        /**
        * Get updates for tag
        *
        * @param string $tag tag name
        * @param int $since_id status ID - will return statuses with newest ID then it
        * @param int $limit
        * @param int $offset
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($tag, $since_id=null, $limit=10, $offset=0) {
            if (!$tag) {
                throw new UnexpectedValueException ('Tag name is missing.', -1);
            }

            $url = '/tags/'.$tag;

            if ($since_id) {
                $url .= '/since/' . (int)$since_id;
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

