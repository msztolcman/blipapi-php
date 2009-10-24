<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.21
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

/**
 * Blip! (http://blip.pl) communication library.
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.21
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi
 */

if (!class_exists ('BlipApi')) {

    require_once 'OAuth.php';

    interface IBlipApi_Command { }

    /**
     * Function registered for SPL autoloading - load required class
     *
     * @param array $class_name
     */
    function BlipApi__autoload ($class_name) {
        if (substr ($class_name, 0, 8) == 'BlipApi_') {
            include strtolower ($class_name).'.php';
        }
    }
    spl_autoload_register ('BlipApi__autoload');

    class BlipApi extends BlipApi_Abstract {
        /**
         * CURL handler
         *
         * @access protected
         * @var resource
         */
        protected $_ch;

        /**
         * Useragent
         *
         * @access protected
         * @var string
         */
        protected $_uagent      = 'BlipApi.php/0.02.21 (http://blipapi.googlecode.com)';

        /**
         *
         *
         * @access protected
         * @var string
         */
        protected $_referer     = 'http://urzenia.net';

        /**
         * URI to API host
         *
         * @access protected
         * @var string
         */
        protected $_root        = 'http://api.blip.pl';

        /**
         *
         *
         * @access protected
         * @var string
         */
        protected $_timeout     = 10;

        /**
         * Debug mode flag
         *
         * @access protected
         * @var bool
         */
        protected $_debug       = false;

        /**
         * Debug message type
         *
         * @access protected
         * @var bool
         */
        protected $_debug_tpl   = array ('', '');

        /**
         * Parser for JSON format
         *
         * This needs to contain name of the function for parsing JSON.
         * Alternatively it may be an array with object and its method name:
         * array ($json, 'decode')
         *
         * @access protected
         * @var array
         */
        protected $_parser     = 'json_decode';

        public $_oauth;
        protected $_consumer_key;
        protected $_consumer_secret;

        /**
         * BlipApi constructor
         *
         * Initialize CURL handler ({@link $_ch}). Throws RuntimeException exception if no CURL extension found.
         *
         * @param string $oauth_key
         * @param string $oauth_secret
         */
        public function __construct ($consumer_key=null, $consumer_secret=null) {
            if (!function_exists ('curl_init')) {
                throw new RuntimeException ('CURL missing!', -1);
            }

            $this->_consumer_key    = $consumer_key;
            $this->_consumer_secret = $consumer_secret;

            # inicjalizujemy szablon dla debugow
            $this->debug_html = false;
        }

        function get_request_token ($oauth_callback=null) {
            $args = array ();
            if ($oauth_callback) {
                $args['oauth_callback'] = $oauth_callback;
            }
            $this->_oauth = new BlipApi_OAuth ($this->_consumer_key, $this->_consumer_secret);
            return $this->_oauth->get_request_token ($args);
        }

        function get_authorize_url ($oauth_token) {
            return $this->_oauth->get_authorize_url ($oauth_token);
        }

        function get_access_token ($oauth_token, $oauth_token_secret, $oauth_verifier=null) {
            $this->_oauth = new BlipApi_OAuth (
                $this->_consumer_key,
                $this->_consumer_secret,
                $oauth_token,
                $oauth_token_secret
            );
            $args = array ();
            if ($oauth_verifier) {
                $args['oauth_verifier'] = $oauth_verifier;
            }
            return $this->_oauth->get_access_token ($args);
        }

        function authorize ($oauth_token, $oauth_token_secret) {
            $this->_oauth = new BlipApi_OAuth (
                $this->_consumer_key,
                $this->_consumer_secret,
                $oauth_token,
                $oauth_token_secret
            );
            return 1;
        }

        /**
         * Magic method to execute commands as their names - it makes all dirty job...
         *
         * @param string $fn name of command
         * @param array $args arguments
         * @access public
         * @return return of {@link execute}
         */
        public function __call ($method_name, $args) {
            if (count ($args) < 1) {
                throw new InvalidArgumentException ('Missing method object.');
            }
            else if (!($args[0] instanceof IBlipApi_Command)) {
                throw new InvalidArgumentException ('Unknown command: '.(is_object ($args[0]) ? get_class ($args[0]) : gettype ($args[0])));
            }
            else if (
                !in_array ($method_name, array ('create', 'read', 'update', 'delete')) ||
                !method_exists ($args[0], $method_name)
            ) {
                throw new BadMethodCallException ("Unknown method \"$method_name\".");
            }

            # wywołujemy znalezioną metodę aby pobrac dane dla requestu
            $method_data    = call_user_func (array ($args[0], $method_name));
            $reply          = $this->_oauth->request ($this->_root . $method_data[0], $method_data[1], null, true);
            $reply          = $this->__parse_reply ($reply);

            if ($reply['status_code'] >= 400) {
                throw new RuntimeException ($reply['status_body'], $reply['status_code']);
            }

            return $reply;
        }

        /**
         * Setter for {@link $_debug} property
         *
         * @param bool $enable
         * @access protected
         */
        protected function __set_debug ($enable = null) {
            $this->_debug = $enable ? true : false;

            curl_setopt($this->_ch, CURLOPT_VERBOSE, $this->_debug);
        }

        /**
         * Setter for {@link $_debug_html} property
         *
         * @param bool $enable
         * @access protected
         */
        protected function __set_debug_html ($enable = null) {
            if ($enable) {
                $this->_debug_tpl = array (
                    "<pre style='border: 1px solid black; padding: 4px;'><b>DEBUG MSG:</b>\n",
                    "</pre>\n",
                );
            }
            else {
                $this->_debug_tpl = array (
                    "DEBUG MSG:\n",
                    "\n",
                );
            }
        }

        /**
         * Setter for {@link $_uagent} property
         *
         * @param string $uagent
         * @access protected
         */
        protected function __set_uagent ($uagent) {
            $this->_uagent = (string) $uagent;
            curl_setopt ($this->_ch, CURLOPT_USERAGENT, $this->_uagent);
        }

        /**
         * Setter for {@link $_referer} property
         *
         * @param string $referer
         * @access protected
         */
        protected function __set_referer ($referer) {
            $this->_referer = (string) $referer;
            curl_setopt ($this->_ch, CURLOPT_REFERER, $referer);
        }

        /**
         * Setter for {@link $_parser} property
         *
         * @param mixed $parser string|array - arguments for call_user_func
         * @access protected
         */
        protected function __set_parser ($data) {
            if (
                (is_string ($parser) && function_exists ($parser))
                ||
                (
                    is_array ($parser) && count ($parser) == 2 &&
                        (
                            (is_object ($parser[0]) && method_exists ($parser[0], $parser[1]))
                            ||
                            (is_string ($parser[0]) && class_exists ($parser[0]) && method_exists ($parser[0], $parser[1]))
                        )
                )
            ) {
                $this->_parser = $parser;
            }

            else {
                if (is_array ($parser)) {
                    $parser = (is_string ($parser[0]) ? $parser[0] : get_class ($parser[0])) . '::' . $parser[1];
                }
                throw new BadFunctionCallException ('Specified parser not found: '. $parser .'.');
            }
        }

        /**
         * Setter for {@link $_timeout} property
         *
         * @param string $timeout
         * @access protected
         */
        protected function __set_timeout ($timeout) {
            $this->_timeout = (int) $timeout;
            curl_setopt ($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
        }

        /**
         * Parsing headers parameter to correct format
         *
         * Param $headers have to be an array, where key is header name, and value - header value, or string in
         * 'Header-Name: Value'.
         * Throws InvalidArgumentException of incorect type of $headers is given
         *
         * @param array|string $headers
         * @access protected
         */
        protected function _parse_headers ($headers) {
            if (!$headers) {
                $headers = array ();
            }
            else if (is_string ($headers) && preg_match ('/^(\w+):\s*(.*)/', $headers, $match)) {
                $headers = array ( $match[1] => $match[2] );
            }
            else if (!is_array ($headers)) {
                throw new InvalidArgumentException (sprintf ('%s::$headers have to be an array or string, but %s given.',
                    __CLASS__,
                    gettype ($headers)), -1
                );
            }

            return $headers;
        }

        /**
         * Create connection with CURL, setts some CURL options etc
         *
         * Throws RuntimeException exception when CURL initialization has failed
         *
         * @param string $login as in {@link __construct}
         * @param string $passwd as in {@link __construct}
         * @access public
         * @return bool always true
         */
        public function connect () {
            # standardowe opcje curla
            $curlopts = array (
                CURLOPT_USERAGENT       => $this->uagent,
                CURLOPT_RETURNTRANSFER  => 1,
                CURLOPT_HEADER          => true,
                CURLOPT_HTTP200ALIASES  => array (201, 204),
                CURLOPT_CONNECTTIMEOUT  => 10,
            );

            # ustawiamy opcje
            curl_setopt_array ($this->_ch, $curlopts);

            return true;
        }

        /**
         * Execute command and parse reply
         *
         * Throws InvalidArgumentException exception when specified command does not exists, or RuntimeException
         * when exists some CURL error or returned status code is greater or equal 400.
         *
         * Internally using magic method BlipApi::__call.
         *
         * @param string $command command to execute
         * @param mixed $options,... options passed to proper command method (prefixed with _cmd__)
         * @access public
         * @return array like {@link __call}
         */
        public function execute () {
            if (!func_num_args ()) {
                throw new InvalidArgumentException ('Command missing.', -1);
            }
            $args   = func_get_args ();
            $fn     = array_shift ($args);
            return call_user_func_array (array ($this, $fn), $args);
        }

        /**
         * Print debug mesage if debug mode is enabled
         *
         * @param string $msg,... messages to print to stdout
         * @access protected
         * @return bool
         */
        protected function _debug () {
            if (!$this->_debug) {
                return;
            }

            $args = func_get_args ();

            echo $this->_debug_tpl[0];
            foreach ($args as $i=>$arg) {
                printf ("%d. %s\n", $i++, print_r ($arg, 1));
            }
            echo $this->_debug_tpl[1];

            return 1;
        }

        /**
         * Return array with CURLOPT_* constants values replaced by these names. For debugging purposes only.
         *
         * @param array $opts array with CURLOPTS_* as keys
         * @return array the same as $opts, but keys are replaced by names of constants
         * @access protected
         */
        protected function _debug_curlopts ($opts) {
            $copts = array ();
            foreach (get_defined_constants () as $k => $v) {
                if (strlen ($k) > 8 && substr ($k, 0, 8) == 'CURLOPT_') {
                    $copts[$v] = $k;
                }
            }

            $ret = array ();
            foreach ($opts as $k => $v) {
                if (isset ($copts, $k)) {
                    $ret[$copts[$k]] = $v;
                }
                else {
                    $ret[$k] = $v;
                }
            }

            return $ret;
        }

        /**
         * Parse reply
         *
         * Throws BadFunctionCallException exception when specified parser was not found.
         * Return array with keys
         *  * headers - (array) array of headers (keys are lowercased)
         *  * body - (mixed) body of response. If reply's mime type is found in {@link $_parser}, then contains reply of specified parser, in other case contains raw string reply.
         *  * body_parse - (bool) if true, content was successfully parsed by specified parser
         *  * status_code - (int) status code from server
         *  * status_body - (string) content of status
         *
         * @param string $reply
         * @return array
         * @access protected
         */
        protected function __parse_reply ($reply) {
            ## rozdzielamy nagłówki od treści
            $body          = preg_split ("/\r?\n\r?\n/mu", $reply, 2);
            ## HTTP1.1 pozwala na wyslanie kilku czesci naglowkow, oddzielonych znakiem nowej linii.
            if (isset ($body[1])) {
                while (strtolower (substr ($body[1], 0, 5)) == 'http/') {
                    $body = preg_split ("/\r?\n\r?\n/mu", $body[1], 2);
                }
            }
            $headers        = $body[0];
            $body           = isset ($body[1]) ? $body[1] : '';

            # parsujemy nagłówki
            $headers        = preg_split ("!\r?\n!", $headers);

            # usuwamy typ protokołu
            $header_http    = array_shift ($headers);
            $headers_parsed = array ();
            $header_name    = '';
            foreach ($headers as $header) {
            	if ($header[0] == ' ' || $header[0] == "\t") {
            		$headers_parsed[$header_name] .= trim ($header);
            	}
            	else {
                    $header                         = preg_split ('/\s*:\s*/', trim ($header), 2);
                    $header_name                    = strtolower ($header[0]);
                    $headers_parsed[$header_name]   = $header[1];
                }
            }
            $headers = &$headers_parsed;

            # określamy kod statusu
            if (
                (isset ($headers['status']) && preg_match ('/(\d+)\s+(.*)/u', $headers['status'], $match))
                ||
                (preg_match ('!HTTP/(1\.[01])\s+(\d+)\s+([\w ]+)!', $header_http, $match))
            ) {
                $status = array ( $match[1], $match[2], $match[3] );
            }
            else {
                $status = array (1.0, 0, '');
            }

            # parsujemy treść odpowiedzi, jeśli mamy odpowiedni parser
            $body_parsed    = false;
            $body_tmp       = call_user_func ($this->_parser, $body);
            if ($body_tmp) {
                $body_parsed    = true;
                $body           = $body_tmp;
            }

            return array (
                'headers'       => $headers,
                'body'          => $body,
                'body_parsed'   => $body_parsed,
                'http_version'  => $status[0],
                'status_code'   => $status[1],
                'status_body'   => $status[2],
            );
        }
    }
}

// vim: fdm=manual
