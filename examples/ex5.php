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

$b_read             = new BlipApi_User ();
$b_read->include    = array ('current_status', 'avatar', 'pictures');

try {
    $response = $blipapi->read ($b_read);
}
catch (RuntimeException $e) {
    printf ("Blip Error: [%d] %s\n\n%s", $e->getCode (), $e->getMessage (), $e->getTraceAsString ());
    exit ();
}

if ($response['status_code'] == 200) {
    $body = $response['body'];

    printf ("Login: %s
Płeć: %s
Lokalizacja: %s
Adres awataru: %s
Ostatni status: %s
Ostatni status poprzez: %s
Obrazek ze statusu: %s\n",

        $body->login,
        $body->sex ? ($body->sex == 'm' ? 'mężczyzna' : 'kobieta') : 'nieznana',
        $body->location ? $body->location : 'nieznana',
        $body->avatar ? $body->avatar->url : 'brak',
        $body->current_status ? $body->current_status->body : 'brak',
        $body->current_status ? $body->current_status->transport->name : 'brak',
        $body->current_status && $body->current_status->pictures ? $body->current_status->pictures[0]->url : 'brak'
    );
}
else {
    printf ("Wrong status: [%d] %s\n", $response['status_code'], $response['status_body']);
}

