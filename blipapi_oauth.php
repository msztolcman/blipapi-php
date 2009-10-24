<?php
require_once('OAuth.php');

class BlipApi_OAuth {
    protected $_consumer;
    protected $_sig_method;
    protected $_token;
    protected $_verifier;
    protected $_curl_options;

    const OAUTH_URL_AUTHORIZE       = 'http://blip.pl/oauth/authorize';
    const OAUTH_URL_TOKEN_ACCESS    = 'http://blip.pl/oauth/access_token';
    const OAUTH_URL_TOKEN_REQUEST   = 'http://blip.pl/oauth/request_token';

    public function set_curl_options ($curl_opts) {
        $this->_curl_options = $curl_opts;
    }

    function __construct ($consumer_key, $consumer_secret, $oauth_token=null, $oauth_token_secret=null) {
        $this->_sig_method  = new OAuthSignatureMethod_HMAC_SHA1 ();
        $this->_consumer    = new OAuthConsumer ($consumer_key, $consumer_secret);
        if ($oauth_token && $oauth_token_secret) {
            $this->_token = new OAuthConsumer ($oauth_token, $oauth_token_secret);
        }
    }

    function get_request_token ($args=null) {
        $r              = $this->request (self::OAUTH_URL_TOKEN_REQUEST, 'get', $args);
        $token          = $this->parse_response ($r);
        $this->_token   = new OAuthConsumer ($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    function parse_response ($responseString) {
        $r = array ();
        foreach (explode ('&', $responseString) as $param) {
            $pair = explode ('=', $param, 2);
            if (count ($pair) != 2) {
                continue;
            }
            $r[urldecode ($pair[0])] = urldecode ($pair[1]);
        }
        return $r;
    }

    function get_authorize_url ($token) {
        if (is_array ($token)) {
            $token = $token['oauth_token'];
        }

        return self::OAUTH_URL_AUTHORIZE . '?oauth_token=' . $token;
    }

    function get_access_token ($verifier=null) {
        $r              = $this->request (self::OAUTH_URL_TOKEN_ACCESS, 'get', array ('oauth_verifier' => $verifier));
        $token          = $this->parse_response ($r);
        $this->_token   = new OAuthConsumer ($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    function request ($url, $method, $args=null, $with_headers=false) {
        $req = OAuthRequest::from_consumer_and_token ($this->_consumer, $this->_token, $method, $url, $args);
        $req->sign_request ($this->_sig_method, $this->_consumer, $this->_token);
        echo "\nREQUEST:\n$url\n".$req->to_url ()."\n";
        switch ($method) {
            case 'get':
                return $this->http ($req->to_url (), null, $with_headers);
            default:
                return $this->http ($req->get_normalized_http_url (), $req->to_postdata (), $with_headers);
        }
    }

    function http ($url, $post_data=null, $with_headers=false) {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_HEADER, $with_headers);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array (
                'Accept: application/json',
                'X-Blip-API: 0.02',
        ));
        if (isset ($post_data)) {
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        if ($this->_curl_options) {
            curl_setopt_array ($ch, $this->_curl_options);
        }

        $response = curl_exec ($ch);
        if (!$response) {
            throw new RuntimeException ('CURL Error: '. curl_error ($ch), curl_errno ($ch));
        }

        curl_close ($ch);
        return $response;
    }
}

