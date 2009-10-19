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

if (!class_exists ('BlipApi_User')) {
    class BlipApi_User extends BlipApi_Abstract implements IBlipApi_Command {
        /**
          * Include some additional data in respond to read method.
          * More info: http://www.blip.pl/api-0.02.html#parametry
          *
          * @access protected
          * @var string|array
          */
        protected $_include;

        /**
         * User name
         *
         * @access protected
         * @var string
         */
        protected $_user;

        /**
         * Setter for field: include
         *
         * @param string $value
         * @access protected
         */
        protected function __set_include ($value) {
            $this->_include = $this->__validate_include ($value);
        }

        /**
         * Setter for field: user
         *
         * @param string $value
         * @access protected
         */
        protected function __set_user ($value) {
            $this->_user = $value;
        }

        /**
         * Return users data
         *
         * @access public
         * @return array parameters for BlipApi::__call
         */
        public function read () {
            if ($this->_user) {
                $url = "/users/$this->_user";
            }
            else {
                $url = '/profile';
            }

            $params = array ();
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

