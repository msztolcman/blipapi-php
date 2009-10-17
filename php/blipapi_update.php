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

if (!class_exists ('BlipApi_Update')) {
    class BlipApi_Update implements IBlipApi_Command {
        /**
        * Creating update
        *
        * @param string $body body of status
        * @param string $user recipient of message
        * @param string @picture Absolute path to a picture assigned to update
        * @param bool $private if true, message to user is sent as private
        * @static
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function create ($body, $user=null, $picture=null, $private=false) {
            if (!$body) {
                throw new UnexpectedValueException ('Update body is missing.', -1);
            }

            if ($user) {
                $body = ($private ? '>' : '') . ">$user $body";
            }

            $opts = array();
            $data = array('update[body]' => $body);
            if ($picture !== null) {
                if ($picture[0] != '@') {
                    $picture = '@'.$picture;
                }
                $data['update[picture]'] = $picture;
                $opts['multipart'] = true;
            }

            return array ('/updates', 'post', $data, $opts);
        }

        /**
        * Reading update
        *
        * It's hard to explain what are doing specified parameters. Please consult with offcial API
        * documentation: {@link http://www.blip.pl/api-0.02.html}.
        *
        * Differences with official API: if you want messages from all users, specify $user == __all__.
        *
        * @param int $id Update ID
        * @param string $user
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param bool $since_id
        * @param int $limit
        * @param int $offset
        * @static
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($id=null, $user=null, $include=array(), $since_id=false, $limit=10, $offset=0) {
            # normalnie pobieramy updatey z tego zasobu
            $url = '/updates';

            # w odróżnieniu od samego API, chcemy ujednolicić pobieranie danych. podanie jako usera '__all__'
            # powoduje pobranie update'ów od wszystkich userów. Układ RESTowych urli blipa jest co najmniej...
            # dziwny... i mało konsekwentny.
            if ($user) {
                # ten user nie istnieje, wprowadzamy go dla wygody użytkownika biblioteka.
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
                    $url = "/users/$user/updates";
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
                $url .= '?' . BlipApi__arr2qstr ($params);
            }

            return array ($url, 'get');
        }

        /**
        * Deleting update
        *
        * Throws UnexpectedValueException when update ID is missing.
        *
        * @param int $id update ID
        * @static
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function delete ($id) {
            if (!$id) {
                throw new UnexpectedValueException ('Update ID is missing.', -1);
            }
            return array ('/updates/'. $id, 'delete');
        }
    }
}

