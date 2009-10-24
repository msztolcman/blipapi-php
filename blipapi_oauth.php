<?php
require_once('OAuth.php');

class BlipApi_OAuth {
    protected $_consumer;
    protected $_http_status;
    protected $_last_api_call;
    protected $_sha1_method;
    protected $_verifier;
    protected $_token;

    const OAUTH_URL_AUTHORIZE       = 'http://blip.pl/oauth/authorize';
    const OAUTH_URL_TOKEN_ACCESS    = 'http://blip.pl/oauth/access_token';
    const OAUTH_URL_TOKEN_REQUEST   = 'http://blip.pl/oauth/request_token';

    function last_status_code () {
        return $this->_http_status;
    }

    function last_api_call () {
        return $this->_last_api_call;
    }

    function __construct ($consumer_key, $consumer_secret, $oauth_token=null, $oauth_token_secret=null) {
        $this->_sha1_method = new OAuthSignatureMethod_HMAC_SHA1 ();
        $this->_consumer    = new OAuthConsumer ($consumer_key, $consumer_secret);
        if ($oauth_token && $oauth_token_secret) {
            $this->_token = new OAuthConsumer ($oauth_token, $oauth_token_secret);
        }
        else {
            $this->_token = null;
        }
    }

    function get_request_token ($args=null) {
        $r              = $this->request (self::OAUTH_URL_TOKEN_REQUEST, 'get', $args);
        $token          = $this->parse_response ($r);
        echo "get_request_token\n";
        print_r ($r);
        print_r ($token);
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

    function get_access_token ($args=null) {
        $r              = $this->request (self::OAUTH_URL_TOKEN_ACCESS, 'get', $args);
        $token          = $this->parse_response ($r);
        echo 'get_access_token';
        print_r ($r);
        print_r ($token);
        $this->_token   = new OAuthConsumer ($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    function request ($url, $method, $args=null, $opts=null, $curlopts=null) {
        $req = OAuthRequest::from_consumer_and_token ($this->_consumer, $this->_token, $method, $url, $args);
        $req->sign_request ($this->_sha1_method, $this->_consumer, $this->_token);
        echo "REQUEST:\n";
        print_r ($req->to_url ());
#         exit;
        $curlopts=null;
        switch ($method) {
            case 'get': return $this->http ($req->to_url (), null, $curlopts);
            default: return $this->http ($req->get_normalized_http_url (), $req->to_postdata (), $curlopts);
        }
    }

    function http ($url, $post_data=null, $curlopts=null) {
        $ch = curl_init ();
        if ($curlopts) {
            curl_setopt_array ($ch, $curlopts);
        }
        else {
            echo "\nURL: $url\n";
            curl_setopt ($ch, CURLOPT_HEADER, true);
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            if (isset ($post_data)) {
                curl_setopt ($ch, CURLOPT_POST, 1);
                curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
            }
        }

        $response = curl_exec ($ch);
        $this->_http_status = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
        $this->_last_api_call = $url;
        curl_close ($ch);
        echo "RESPONSE:\n";
        print_r ($response);die;

        ## rozdzielamy nagłówki od treści
        $response          = preg_split ("/\r?\n\r?\n/mu", $response, 2);
        ## HTTP1.1 pozwala na wyslanie kilku czesci naglowkow, oddzielonych znakiem nowej linii.
        while (strtolower (substr ($response[1], 0, 5)) == 'http/') {
            $response = preg_split ("/\r?\n\r?\n/mu", $response[1], 2);
        }
        $response           = isset ($response[1]) ? $response[1] : '';

        return $response;
    }
}

