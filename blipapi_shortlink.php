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

if (!class_exists ('BlipApi_Shortlink')) {
    class BlipApi_Shortlink extends BlipApi_Abstract implements IBlipApi_Command {
        protected $_code;
        protected $_limit       = 10;
        protected $_link;
        protected $_offset      = 0;
        protected $_since_id;

        protected function __set_code ($value) {
            $this->_code = $value;
        }
        protected function __set_limit ($value) {
            $this->_limit = $this->__validate_limit ($value);
        }
        protected function __set_link ($value) {
            $this->_link = $value;
        }
        protected function __set_offset ($value) {
            $this->_offset = $this->__validate_offset ($value);
        }
        protected function __set_since_id ($value) {
            $this->_since_id = $this->__validate_offset ($value);
        }

        /**
        * Create shortlink
        *
        * Throws InvalidArgumentException if url is missing.
        *
        * @access public
        * @return array parameters for BlipApi::__call
        */
        public function create () {
            if (!$this->_link) {
                throw new InvalidArgumentException ("Url is missing.");
            }

            $url = '/shortlinks';
            $data = array ();
            $data['shortlink[original_link]'] = $this->_link;

            return array ($url, 'post', $data);
        }

        /**
        * Get shortlinks from Blip!'s rdir system
        *
        * @access public
        * @return array parameters for BlipApi::__call
        */
        public function read () {
            if ($this->_code) {
                $url = "/shortlinks/$this->_code";
            }
            else if ($this->_since_id) {
                $url = "/shortlinks/$this->_since_id/all_since";
            }
            else {
                $url = '/shortlinks/all';
            }

            $params = array ();
            if ($this->_limit) {
                $params['limit'] = $this->_limit;
            }
            if ($this->_offset) {
                $params['offset'] = $this->_offset;
            }
            if (count ($params)) {
                $url .= '?'.BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

