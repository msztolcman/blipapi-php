<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * Tool to request TOKEN data
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.32
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)
 * @package blipapi_tools
 */

define ('ROOT', dirname (dirname (__FILE__)));
require_once ROOT.'/lib/OAuth.php';
require_once ROOT.'/tools/lib.php';

define ('URL_REQUEST_TOKEN',    'http://blip.pl/oauth/request_token');
define ('URL_ACCESS_TOKEN',     'http://blip.pl/oauth/access_token');
define ('URL_AUTHORIZE',        'http://blip.pl/oauth/authorize');

define ('CALLBACK_URL',         'http://blipapi.googlecode.com');

import_const ();

$oauth_consumer = new OAuthConsumer (CONSUMER_KEY, CONSUMER_SECRET);
$oauth_method   = new OAuthSignatureMethod_HMAC_SHA1 ();
$oauth_request  = OAuthRequest::from_consumer_and_token ($oauth_consumer, null, 'GET', URL_REQUEST_TOKEN, array ('oauth_callback' => CALLBACK_URL));
$oauth_request->sign_request ($oauth_method, $oauth_consumer, null);

echo "Connecting to:\n";
echo $oauth_request->to_url () . "\n\n";

$resp           = shell_exec ("wget -O - -q '".$oauth_request->to_url()."'");
parse_str ($resp, $resp);

$oauth_token    = new OAuthToken ($resp['oauth_token'], $resp['oauth_token_secret']);

$oauth_request  = new OAuthRequest ('GET', URL_AUTHORIZE, array ('oauth_token' => $oauth_token->key));
echo "Visit this URL and accept access::\n".$oauth_request->to_url()."\n\n";
echo "Now enter PIN code from blip.pl: ";

$fh             = fopen ('php://stdin', 'r');
$oauth_verifier = trim (fgets ($fh, 16));
echo "\n\n";

$oauth_request  = OAuthRequest::from_consumer_and_token ($oauth_consumer, $oauth_token, 'GET', URL_ACCESS_TOKEN, array ('oauth_verifier' => $oauth_verifier));
$oauth_request->sign_request ($oauth_method, $oauth_consumer, $oauth_token);

$resp = shell_exec ("wget -O - -q '".$oauth_request->to_url()."'");

parse_str ($resp, $resp_parsed);
echo 'OAuth TOKEN:  '. $resp_parsed['oauth_token'] ."\n";
echo 'OAuth SECRET: '. $resp_parsed['oauth_token_secret'] ."\n\n";

echo "Full response:\n";
echo $resp . "\n";

