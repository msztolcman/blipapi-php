<?php
/**
 * Blip! (http://blip.pl) communication library.
 *
 * Examples
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.32
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)
 * @package blipapi_examples
 */


require '../lib/blipapi.php';
require '../lib/OAuth.php';

define ('CONSUMER_KEY', 'your app key');
define ('CONSUMER_SECRET', 'your app secret');
define ('TOKEN_KEY', 'your token key');
define ('TOKEN_SECRET', 'your token secret');

$oauth_consumer = new OAuthConsumer (CONSUMER_KEY, CONSUMER_SECRET);
$oauth_token    = new OAuthToken (TOKEN_KEY, TOKEN_SECRET);

$blipapi = new BlipApi ($oauth_consumer, $oauth_token);
$blipapi->timeout = 0;

$b_update           = new BlipApi_Update ();
$b_update->body     = 'Testuję #BlipApiPHP ( http://blipapi.googlecode.com ) - obrazek z demotywatory.pl (r)';
$b_update->image    = 'image2.jpg';
$b_update->user     = 'myszapi';

try {
    $response = $blipapi->create ($b_update);
}
catch (RuntimeException $e) {
    printf ("Blip Error: [%d] %s\n\n%s", $e->getCode (), $e->getMessage (), $e->getTraceAsString ());
    exit ();
}

if ($response['status_code'] == 201) {
    printf ("Utworzono status: %s\n", $response['headers']['location']);
}
else {
    printf ("Wrong status: [%d] %s\n", $response['status_code'], $response['status_body']);
}


