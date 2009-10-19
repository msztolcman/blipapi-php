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

if (!class_exists ('BlipApi_Bliposphere')) {
    class BlipApi_Bliposphere extends BlipApi_Abstract implements IBlipApi_Command {
        /**
         * Limit read results to $_limit items
         *
         * @access protected
         * @var int
         */
        protected $_limit   = 10;

        /**
         * Include some additional data in respond to read method.
         * More info: http://www.blip.pl/api-0.02.html#parametry
         *
         * @access protected
         * @var string|array
         */
        protected $_include = null;

        /**
         * Setter for field: limit
         *
         * @param string $value
         * @access protected
         */
        protected function __set_limit ($value) {
            $this->_limit = $this->__validate_limit ($value);
        }

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
         * Return current bliposhpere
         *
         * @access public
         * @return array parameters for BlipApi::__call
         */
        public function read () {
            $url = '/bliposphere';

            $params = array ();
            if ($this->_limit) {
                $params['limit'] = $this->_limit;
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

