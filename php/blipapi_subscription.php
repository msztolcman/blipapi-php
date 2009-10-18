<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.15
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.15
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Subscription')) {
    class BlipApi_Subscription implements IBlipApi_Command {
        /**
        * Return user current subscriptions
        *
        * Throws UnexpectedValueException when incorrect $direction is specified.
        *
        * @param string $user
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param string $direction subscription direction. Can be: both (default), from, to
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($user=null, $include=null, $direction = 'both') {
            $direction = strtolower ($direction);
            if (!in_array ($direction, array ('both', 'to', 'from'))) {
                throw new UnexpectedValueException (sprintf ('Incorrect param: "direction": "%s". Allowed values: both, from, to.',
                    $direction), -1);
            }

            if ($direction == 'both') {
                $direction = '';
            }

            $url = '/subscriptions/' . $direction;
            if (!is_null ($user) && $user) {
                $url = '/users/'. $user . $url;
            }

            $params = array ();
            if ($include) {
                $params['include'] = implode (',', $include);
            }

            if (count ($params)) {
                $url .= '?'.BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }

        /**
        * Create or delete subscription of given user to current signed
        *
        * @param string $user subscribed user
        * @param bool $www
        * @param bool $im
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function update ($user, $www=null, $im=null) {
            $url = '/subscriptions';
            if (!is_null ($user) && $user) {
                $url .= '/'. $user;
            }

            $data = array (
                'subscription[www]' => $www ? 1 : 0,
                'subscription[im]'  => $im  ? 1 : 0,
            );
            return array ($url . '?' . BlipApi__arr2qstr ($data), 'put');
        }

        /**
        * Delete subscription
        *
        * @param string $user
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function delete ($user) {
            return array ('/subscriptions/'. $user, 'delete');
        }
    }
}

