<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.20
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.20
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Background')) {
    class BlipApi_Background extends BlipApi_Abstract implements IBlipApi_Command {
        protected $_user    = '';
        protected $_image   = '';

        protected function __set_user ($value) {
            $this->_user = $value;
        }

        protected function __set_image ($value) {
            $this->_image = $this->__validate_file ($value);
        }

        /**
        * Get info about users background
        *
        * @param string $user
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function read () {
            if (!$this->_user) {
                throw new InvalidArgumentException ('User name is missing.', -1);
            }
            return array ("/users/$this->_user/background", 'get');
        }

        /**
        * Upload new background
        *
        * Throws InvalidArgumentException if background path is missing, or file not found
        *
        * @param string $background new background path
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function update () {
            if (!$this->_image) {
                throw new InvalidArgumentException ('Background path is missing or file not found.', -1);
            }
            return array ('/background', 'post', array ('background[file]' => '@'.$this->_image), array ('multipart' => 1));
        }

        /**
        * Delete background
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function delete () {
            return array ('/background', 'delete');
        }
    }
}

