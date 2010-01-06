<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * Simple testing utility
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.30
 * @version $Id: blipapi.php 136 2010-01-06 18:00:54Z urzenia $
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License v.2
 * @package blipapi_tools
 */

define ('ROOT', dirname (dirname (__FILE__)));

include ROOT.'/lib/blipapi.php';
include ROOT.'/tools/lib.php';
require_once ROOT.'/lib/OAuth.php';

import_const ();

$oauth_consumer = new OAuthConsumer (CONSUMER_KEY, CONSUMER_SECRET);
$oauth_token    = new OAuthToken (TOKEN_KEY, TOKEN_SECRET);

function test () {
    $args   = func_get_args ();
    $type   = array_shift ($args);
    $action = array_shift ($args);

    $type   = 'BlipApi_' . ucfirst (strtolower ($type));

    $c      = new $type ();
    $i_max  = count ($args);
    for ($i=0; $i<$i_max; $i+=2) {
        $key        = $args[$i];
        $c->$key    = $args[$i+1];
    }

    if (isset ($_ENV['SERVER_ADDR'])) {
        echo '<pre>';
    }
    print_r ($GLOBALS['b']->$action ($c));
    if (isset ($_ENV['SERVER_ADDR'])) {
        echo '</pre>';
    }
}

$b = new BlipApi ($oauth_consumer, $oauth_token);
$b->timeout = 0;
# $b = new BlipApi ();

# $b->debug = true;

$i1 = '/Users/mysz/ROZNE/avatar.jpg';
$i2 = '/Users/mysz/test_img/test_jpg/01.jpg';
$t = strftime ('%Y%m%d %H%M%S', time ());

## Avatar
# test ('avatar', 'read', 'user', 'myszapi');
# test ('avatar', 'update', 'image', $i1);
# test ('avatar', 'delete');

## Background
# test ('background', 'read', 'user', 'myszapi');
# test ('background', 'update', 'image', $i1);
# test ('background', 'delete');

## Bliposphere
# test ('bliposphere', 'read', 'limit', 2);
# test ('bliposphere', 'read', 'limit', 2, 'include', array ('user', 'user[avatar]'));

## Dashboard
# test ('dashboard', 'read', 'limit', 2, 'user', 'opi');
# test ('dashboard', 'read', 'limit', 2, 'include', array ('user', 'user[avatar]'));

## Dirmsg
# test ('dirmsg', 'create', 'body', 'qwe ' . $t, 'user', 'myszapi');
# test ('dirmsg', 'create', 'body', 'qwe ' . $t, 'user', 'myszapi', 'image', $i2);
# test ('dirmsg', 'read', 'id', 31482231);
# test ('dirmsg', 'read', 'user', 'myszapi', 'limit', 2);
# test ('dirmsg', 'read', 'user', 'myszapi', 'limit', 2, 'include', array ('user', 'user[avatar]'));
# test ('dirmsg', 'read', 'user', 'myszapi', 'limit', 2, 'include', array ('user', 'user[avatar]'), 'offset', 1);
# test ('dirmsg', 'delete', 'id', 31482231);
#
## Movie
# test ('movie', 'read', 'id', 8337417);

## Notice
# test ('notice', 'read', 'user', 'myszapi', 'limit', 2);
# test ('notice', 'read', 'user', 'myszapi', 'limit', 2, 'offset', 1);
# test ('notice', 'read', 'user', 'myszapi', 'limit', 2, 'offset', 1, 'include', array ('user'));

## Picture
# test ('picture', 'read', 'id', 8336732);
# test ('picture', 'read', 'id', 8336732, 'include', array ('update'));
# test ('picture', 'read', 'limit', 2);
# test ('picture', 'read', 'limit', 2, 'include', array ('update'));

## Privmsg
# test ('privmsg', 'create', 'body', 'qwe ' . $t, 'user', 'myszapi');
# test ('privmsg', 'create', 'body', 'qwe ' . $t, 'user', 'myszapi', 'image', $i2);
# test ('privmsg', 'read', 'id', 31531016);
# test ('privmsg', 'read', 'limit', 2);
# test ('privmsg', 'read', 'limit', 2, 'include', array ('user', 'user[avatar]'));
# test ('privmsg', 'read', 'limit', 2, 'include', array ('user', 'user[avatar]'), 'offset', 1);
# test ('privmsg', 'delete', 'id', 31531016);

## Recording
# test ('recording', 'read', 'id', 'NIEZNANE');

## Shortlink
# test ('shortlink', 'create', 'link', 'http://asd.qwe.urzenia.net');
# test ('shortlink', 'read', 'code', '371co');
# test ('shortlink', 'read', 'limit', 2);
# test ('shortlink', 'read', 'since_id', 4803669, 'limit', 3);

## Status
# test ('status', 'create', 'body', 'qwe ' . $t);
# test ('status', 'create', 'body', 'qwe ' . $t, 'image', $i2);
# test ('status', 'read', 'id', 31393068);
# test ('status', 'read', 'user', 'opi', 'limit', 2);
# test ('status', 'read', 'user', 'myszapi', 'limit', 2, 'include', array ('user', 'user[avatar]'));
# test ('status', 'read', 'user', 'myszapi', 'limit', 2, 'include', array ('user', 'user[avatar]'), 'offset', 1);
# test ('status', 'delete', 'id', 31393068);

## Subscription
# test ('subscription', 'read', 'direction', 'to');
# test ('subscription', 'read', 'direction', 'from');
# test ('subscription', 'read', 'direction', 'to', 'include', 'tracking_user');
# test ('subscription', 'update', 'user', 'mysz', 'www', true);
# test ('subscription', 'update', 'user', 'mysz', 'www', false);
# test ('subscription', 'delete', 'user', 'mysz');

## Tag
# test ('tag', 'read', 'tag', 'wpblip');
# test ('tag', 'read', 'tag', 'wpblip', 'limit', 3);
# test ('tag', 'read', 'tag', 'wpblip', 'limit', 3, 'include', 'user');

## Update
# test ('update', 'create', 'body', 'qwe ' . $t);
# test ('update', 'create', 'body', 'qwe ' . $t, 'user', 'myszapi', 'image', $i2);
# test ('update', 'create', 'body', 'qwe ' . $t, 'user', 'myszapi', 'image', $i2, 'private', true);
# test ('update', 'read', 'id', 31552664);
# test ('update', 'read', 'user', 'opi', 'limit', 2);
# test ('update', 'read', 'user', 'myszapi', 'limit', 2, 'include', array ('user', 'user[avatar]'));
# test ('update', 'delete', 'id', 31543467);

## User
# test ('user', 'read');
# test ('user', 'read', 'include', array ('current_status', 'avatar'));

