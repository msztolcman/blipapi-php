<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.21
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.21
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Recording')) {
    class BlipApi_Recording extends BlipApi_Abstract implements IBlipApi_Command {
        /**
         * ID of item to read
         *
         * @access protected
         * @var int
         */
        protected $_id;

        /**
         * Setter for field: id
         *
         * @param string $value
         * @access protected
         */
        protected function __set_id ($value) {
            $this->_id = $this->__validate_offset ($value);
        }

        /**
         * Read recording attached to status/message/update
         *
         * Throws InvalidArgumentException when status ID is missing
         *
         * @access public
         * @return array parameters for BlipApi::__call
         */
        public function read () {
            if (!$this->_id) {
                throw new InvalidArgumentException ('Update ID is missing.', -1);
            }
            return array ("/updates/$this->_id/recording", 'get');
        }
    }
}

