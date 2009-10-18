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

if (!class_exists ('BlipApi_Picture')) {
    class BlipApi_Picture extends BlipApi_Abstract implements IBlipApi_Command {
        protected $_id;
        protected $_include;
        protected $_limit       = 10;
        protected $_offset      = 0;
        protected $_since_id;

        protected function __set_id ($value) {
            $this->_id = $this->__validate_offset ($value);
        }
        protected function __set_include ($value) {
            $this->_include = $this->__validate_include ($value);
        }
        protected function __set_limit ($value) {
            $this->_limit = $this->__validate_limit ($value);
        }
        protected function __set_offset ($value) {
            $this->_offset = $this->__validate_offset ($value);
        }
        protected function __set_since_id ($value) {
            $this->_since_id = $this->__validate_offset ($value);
        }

        /**
        * Read picture attached to status/message/update
        *
        * Throws InvalidArgumentException when update ID is missing
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */

        public function read () {
            if ($this->_since_id) {
                $url = "/pictures/$this->_since_id/all_since";
            }
            else if ($this->_id) {
                $url = "/updates/$this->_id/pictures";
            }
            else {
                $url = "/pictures/all";
            }

            $params = array ();
            if ($this->_limit) {
                $params['limit'] = $this->_limit;
            }
            if ($this->_offset) {
                $params['offset'] = $this->_offset;
            }
            if ($this->_include) {
                $params['include'] = implode (',', $this->_include);
            }

            if (count ($params)) {
                $url .= '?'.BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

