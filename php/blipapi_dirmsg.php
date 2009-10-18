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

if (!class_exists ('BlipApi_Dirmsg')) {
    class BlipApi_Dirmsg extends BlipApi_Abstract implements IBlipApi_Command {
        protected $_body;
        protected $_id;
        protected $_include;
        protected $_limit   = 10;
        protected $_offset  = 0;
        protected $_image;
        protected $_since_id;
        protected $_user;

        protected function __set_body ($value) {
            $this->_body = $value;
        }
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
        protected function __set_image ($value) {
            $this->_image = $this->__validate_file ($value);
        }
        protected function __set_since_id ($value) {
            $this->_since_id = $this->__validate_offset ($value);
        }
        protected function __set_user ($value) {
            $this->_user = $value;
        }

        /**
        * Create direct message
        *
        * Throws UnexpectedValueException if some of parametr is missing.
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function create () {
            if (!$this->_body || !$this->_user) {
                throw new UnexpectedValueException ('Directed_message body or recipient is missing.', -1);
            }
            $opts = array();
            $data = array('directed_message[body]' => $this->_body, 'directed_message[recipient]' => $this->_user);
            if ($this->_image !== null) {
                $data['directed_message[picture]'] = '@'.$this->_image;
                $opts['multipart'] = true;
            }
            return array ('/directed_messages', 'post', $data, $opts);
        }

        /**
        * Read direct message
        *
        * Meaning of params: {@link http://www.blip.pl/api-0.02.html}
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function read () {
            # normalnie pobieramy mesgi z tego zasobu
            if ($this->_user) {
                if ($this->_user == '__ALL__') {
                    if ($this->_since_id) {
                        $url = "/directed_messages/$this->_since_id/all_since";
                    }
                    else {
                        $url = "/directed_messages/all";
                    }
                }
                else {
                    if ($this->_since_id) {
                        $url = "/users/$this->_user/directed_messages/$this->_since_id/since";
                    }
                    else {
                        $url = "/users/$this->_user/directed_messages";
                    }
                }
            }
            else if ($this->_id) {
                $url = "/directed_messages/$this->_id";
            }

            else {
                $url = '/directed_messages';
                if ($this->_since_id) {
                    $url .= "/$this->_since_id/since";
                }
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

        /**
        * Delete direct message
        *
        * Throws UnexpectedValueException when directed message ID is missing
        *
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public function delete () {
            if (!$this->_id) {
                throw new UnexpectedValueException ('Directed_message ID is missing.', -1);
            }
            return array ('/directed_messages/'. $this->_id, 'delete');
        }
    }
}

