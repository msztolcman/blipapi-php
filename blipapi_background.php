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

if (!class_exists ('BlipApi_Background')) {
    class BlipApi_Background implements IBlipApi_Command {
        /**
        * Get info about users background
        *
        * @param string $user
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($user=null) {
            if (!$user) {
                throw new UnexpectedValueException ('User name is missing.', -1);
            }
            return array ("/users/$user/background", 'get');
        }

        /**
        * Upload new background
        *
        * Throws UnexpectedValueException if background path is missing, or file not found
        *
        * @param string $background new background path
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function update ($background) {
            if (!$background || !file_exists ($background)) {
                throw new UnexpectedValueException ('Background path is missing or file not found.', -1);
            }
            if ($background[0] != '@') {
                $background = '@'.$background;
            }
            return array ('/background', 'post', array ('background[file]' => $background), array ('multipart' => 1));
        }

        /**
        * Delete background
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function delete () {
            return array ('/background', 'delete');
        }
    }
}

