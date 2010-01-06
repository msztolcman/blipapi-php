<?php
/**
 * Blip! (http://blip.pl) communication library.
 *
 * Examples
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.30
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
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

$b_dirmsg          = new BlipApi_Privmsg ();
$b_dirmsg->limit   = 3;
$b_dirmsg->include = array ('user', 'recipient');

try {
    $response = $blipapi->read ($b_dirmsg);
}
catch (RuntimeException $e) {
    printf ("Blip Error: [%d] %s\n\n%s", $e->getCode (), $e->getMessage (), $e->getTraceAsString ());
    exit ();
}

if ($response['status_code'] == 200) {
    foreach ($response['body'] as $body) {
        printf ("%s [% 20s -> %-20s] %s\n", $body->created_at, $body->user->login, $body->recipient->login, $body->body);
    }
}
else {
    printf ("Wrong status: [%d] %s\n", $response['status_code'], $response['status_body']);
}

