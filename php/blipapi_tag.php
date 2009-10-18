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

if (!class_exists ('BlipApi_Tag')) {
    class BlipApi_Tag extends BlipApi_Abstract implements IBlipApi_Command {
        protected $_include;
        protected $_limit       = 10;
        protected $_since_id;
        protected $_tag;

        protected function __set_include ($value) {
            $this->_include = $this->__validate_include ($value);
        }
        protected function __set_limit ($value) {
            $this->_limit = $this->__validate_limit ($value);
        }
        protected function __set_since_id ($value) {
            $this->_since_id = $this->__validate_offset ($value);
        }
        protected function __set_tag ($value) {
            $this->_tag = $value;
        }

        /**
        * Get updates for tag
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function read () {
            if (!$this->_tag) {
                throw new InvalidArgumentException ('Tag name is missing.', -1);
            }

            $url = "/tags/$this->_tag";

            if ($this->_since_id) {
                $url .= "/since/$this->_since_id";
            }

            $params = array ();
            if ($this->_limit) {
                $params['limit'] = $this->_limit;
            }
            if ($this->_include) {
                $params['include'] = implode (',', $this->_include);
            }

            if (count ($params)) {
                $url .= '?' . BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }
    }
}

