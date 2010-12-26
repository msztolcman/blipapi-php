<?php
/**
 * Blip! (http://blip.pl) communication library.
 *
 * Examples
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.32
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)
 * @package blipapi_examples
 */


require '../lib/blipapi.php';

$blipapi = new BlipApi ();

$b_status = new BlipApi_Status ();
$b_status->user     = 'mysz';
$b_status->limit    = 2;

try {
    $response = $blipapi->read ($b_status);
}
catch (RuntimeException $e) {
    printf ("Blip Error: [%d] %s\n\n%s", $e->getCode (), $e->getMessage (), $e->getTraceAsString ());
    exit ();
}

if ($response['status_code'] == 200) {
    foreach ($response['body'] as $body) {
        printf ("%s %s\n", $body->created_at, $body->body);
    }
}
else {
    printf ("Wrong status: [%d] %s\n", $response['status_code'], $response['status_body']);
}

