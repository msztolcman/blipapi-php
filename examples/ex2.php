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

$blipapi = new BlipApi ();

$b_bliposphere          = new BlipApi_Bliposphere ();
$b_bliposphere->limit   = 10;
$b_bliposphere->include = array ('user');

try {
    $response = $blipapi->read ($b_bliposphere);
}
catch (RuntimeException $e) {
    printf ("Blip Error: [%d] %s\n\n%s", $e->getCode (), $e->getMessage (), $e->getTraceAsString ());
    exit ();
}

if ($response['status_code'] == 200) {
    foreach ($response['body'] as $body) {
        printf ("%s [% 20s] %s\n", $body->created_at, $body->user->login , $body->body);
    }
}
else {
    printf ("Wrong status: [%d] %s\n", $response['status_code'], $response['status_body']);
}

