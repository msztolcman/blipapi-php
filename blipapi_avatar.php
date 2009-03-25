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

if (!class_exists ('BlipApi_Avatar')) {
    class BlipApi_Avatar implements IBlipApi_Command {
        /**
        * Get info about users avatar
        *
        * @param string $user
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($user) {
            if (!$user) {
                throw new UnexpectedValueException ('User name is missing.', -1);
            }
            return array ("/users/$user/avatar", 'get');
        }

        /**
        * Upload new avatar
        *
        * Throws UnexpectedValueException if avatar path is missing or file not found
        *
        * @param string $avatar new avatars path
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function update ($avatar) {
            if (!$avatar || !file_exists ($avatar)) {
                throw new UnexpectedValueException ('Avatar path missing or file not found.', -1);
            }
            if ($avatar[0] != '@') {
                $avatar = '@'.$avatar;
            }
            return array ('/avatar', 'post', array ( 'avatar[file]' => $avatar ), array ('multipart' => 1));
        }

        /**
        * Delete avatar
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function delete () {
            return array ('/avatar', 'delete');
        }
    }
}

