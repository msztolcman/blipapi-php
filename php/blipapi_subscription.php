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

if (!class_exists ('BlipApi_Subscription')) {
    class BlipApi_Subscription extends BlipApi_Abstract implements IBlipApi_Command {
        /**
         * Specify whis subscription is read: to user, from user or both.
         * Accepted values:
         *  * to
         *  * from
         *  * both (default)
         *
         * @access protected
         * @var string
         */
        protected $_direction   = 'both';

        /**
         * Affect Instant Messenger subscription
         *
         * @access protected
         * @var bool
         */
        protected $_im;

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
         * Affect WWW subscription
         *
         * @access protected
         * @var bool
         */
        protected $_www;

        protected function __set_direction ($value) {
            if (!in_array ($value, array ('from', 'to', 'both'))) {
                throw new InvalidArgumentException ("Incorrect direction.");
            }

            $this->_direction = $value;
        }
        protected function __set_im ($value) {
            $this->_im = $value;
        }
        protected function __set_include ($value) {
            $this->_include = $this->__validate_include ($value);
        }
        protected function __set_user ($value) {
            $this->_user = $value;
        }
        protected function __set_www ($value) {
            $this->_www = $value;
        }

        /**
         * Return user current subscriptions
         *
         * Throws InvalidArgumentException when incorrect $direction is specified.
         *
         * @access public
         * @return array parameters for BlipApi::__query
         */
        public function read () {
            if ($this->_direction == 'both') {
                $this->_direction = '';
            }

            $url = '/subscriptions/' . $this->_direction;
            if ($this->_user) {
                $url = "/users/$this->_user$url";
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

        /**
         * Create or delete subscription of given user to current signed
         *
         * @access public
         * @return array parameters for BlipApi::__query
         */
        public function update () {
            $url = '/subscriptions';
            if ($this->_user) {
                $url .= "/$this->_user";
            }

            $data = array (
                'subscription[www]' => $this->_www ? 1 : 0,
                'subscription[im]'  => $this->_im  ? 1 : 0,
            );
            return array ($url . '?' . BlipApi__arr2qstr ($data), 'put');
        }

        /**
         * Delete subscription
         *
         * @access public
         * @return array parameters for BlipApi::__query
         */
        public function delete () {
            if (!$this->_user) {
                throw new InvalidArgumentException ("Missing user");
            }

            return array ("/subscriptions/$this->_user", 'delete');
        }
    }
}

