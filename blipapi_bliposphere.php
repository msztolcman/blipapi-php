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
        protected $_limit   = 10;
        protected $_include = null;

        protected function __set_limit ($value) {
            $this->_limit = $this->__validate_limit ($value);
        }

        protected function __set_include ($value) {
            $this->_include = $this->__validate_include ($value);
        }

        /**
        * Return current bliposhpere
        *
        * @access public
        * @return array parameters for BlipApi::__query
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

