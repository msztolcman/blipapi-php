<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.14
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.14
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Dirmsg')) {
    class BlipApi_Dirmsg implements IBlipApi_Command {
        /**
        * Create direct message
        *
        * Throws UnexpectedValueException if some of parametr is missing.
        *
        * @param string $body Body of sent message
        * @param int|string $user username or user id
        * @param string @picture Absolute path to a picture assigned to a message
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function create ($body, $user, $picture = null) {
            if (!$body || !$user) {
                throw new UnexpectedValueException ('Directed_message body or recipient is missing.', -1);
            }
            $opts = array();
            $data = array('directed_message[body]' => $body, 'directed_message[recipient]' => $user);
            if ($picture !== null) {
                if ($picture[0] != '@') {
                    $picture = '@'.$picture;
                }
                $data['directed_message[picture]'] = $picture;
                $opts['multipart'] = true;
            }
            return array ('/directed_messages', 'post', $data, $opts);
        }

        /**
        * Read direct message
        *
        * Meaning of params: {@link http://www.blip.pl/api-0.02.html}
        *
        * @param int $id message ID
        * @param string $user username
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param bool $since_id
        * @param int $limit default to 10
        * @param int $offset default to 0
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($id=null, $user=null, $include=array (), $since_id=false, $limit=10, $offset=0) {
            # normalnie pobieramy mesgi z tego zasobu
            $url = '/directed_messages';

            if (!is_null ($user) && $user) {
                # ten user nie istnieje, wprowadzamy go dla wygody użytkownika biblioteki.
                if (strtolower ($user) == '__all__') {
                    if ($id) {
                        $url    .= '/'. $id;
                        $id     = null;
                    }
                    $url        .= '/all';
                    if ($since_id) {
                        $url    .= '_since';
                        $since_id  = null;
                    }
                }
                # jeśli pobieramy konkretnego usera, to wszystko jest prostsze
                else {
                    $url = "/users/$user/directed_messages";
                }
            }

            # dla pojedynczego usera, innego niż __all__, dodajemy id wpisu
            if (!is_null ($id) && $id) {
                $url .= '/'. $id;
            }

            if ($since_id) {
                $url .= '/since';
            }

            $params = array ();

            $limit = (int)$limit;
            if ($limit) {
                $params['limit'] = $limit;
            }
            $offset = (int)$offset;
            if ($offset) {
                $params['offset'] = $offset;
            }
            if ($include) {
                $params['include'] = implode (',', $include);
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
        * @param int $id message ID
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function delete ($id) {
            if (!$id) {
                throw new UnexpectedValueException ('Directed_message ID is missing.', -1);
            }
            return array ('/directed_messages/'. $id, 'delete');
        }
    }
}

