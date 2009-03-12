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

if (!class_exists ('BlipApi_Bliposphere')) {
    class BlipApi_Bliposphere implements IBlipApi_Command {
        /**
        * Return current bliposhpere
        *
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($include=array ()) {
            $url = '/bliposphere';

            if ($include) {
                $url .= '?include=' . implode (',', $include);
            }

            return array ($url, 'get');
        }
    }
}

