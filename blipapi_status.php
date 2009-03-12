<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.10
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.10
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi_Status')) {
    class BlipApi_Status implements IBlipApi_Command {
        /**
        * Create status
        *
        * Throws UnexpectedValueException when status body is missing
        *
        * @param string $body Body of setted message
        * @param string @picture Absolute path to a picture assigned to a status
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function create ($body, $picture = null) {
            if (!$body) {
                throw new UnexpectedValueException ('Status body is missing.', -1);
            }
            $opts = array();
            $data = array('status[body]' => $body);
            if ($picture !== null) {
                $data['status[picture]'] = $picture;
                $opts['multipart'] = true;
            }
            return array ('/statuses', 'post', $data, $opts);
        }

        /**
        * Read status
        *
        * Meaning of params: {@link http://www.blip.pl/api-0.02.html}
        *
        * @param int $id status ID
        * @param string $user username
        * @param array $include array of resources to include (more info in official API documentation: {@link http://www.blip.pl/api-0.02.html}.
        * @param bool $since
        * @param int $limit default to 10
        * @param int $offset default to 0
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function read ($id=null, $user=null, $include=array (), $since=false, $limit=10, $offset=0) {
            # normalnie pobieramy statusy z tego zasobu
            $url = '/statuses';

            if ($user) {
                # ten user nie istnieje, wprowadzamy go dla wygody użytkownika biblioteki.
                if (strtolower ($user) == '__all__') {
                    if ($id) {
                        $url    .= '/'. $id;
                        $id     = null;
                    }
                    $url        .= '/all';
                    if ($since) {
                        $url    .= '_since';
                        $since  = null;
                    }
                }
                # jeśli pobieramy konkretnego usera, to wszystko jest prostsze
                else {
                    $url = "/users/$user/statuses";
                }
            }

            # dla pojedynczego usera, innego niż __all__, dodajemy id wpisu
            if (!is_null ($id) && $id) {
                $url .= '/'. $id;
            }

            if ($since) {
                $url .= '/since';
            }

            $limit = (int)$limit;
            if ($limit) {
                $url .= '?limit='.$limit;
            }

            $offset = (int)$offset;
            if ($offset) {
                $url .= ($limit ? '&' : '?') . 'offset=' . $offset;
            }

            if ($include) {
                $url .= (($limit || $offset) ? '&' : '?'). 'include=' . implode (',', $include);
            }

            return array ($url, 'get');
        }

        /**
        * Delete status
        *
        * Throws UnexpectedValueException when status ID is missing
        *
        * @param int $id status ID
        * @access public
        * @return array parameters for BlipApi::__query
        */
        public static function delete ($id) {
            if (!$id) {
                throw new UnexpectedValueException ('Status ID is missing.', -1);
            }
            return array ('/statuses/'. $id, 'delete');
        }
    }
}

