<?php

/**
 * Blip! (http://blip.pl) communication library.
 *
 * Common things (functions and settings) for tools
 *
 * @author Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
 * @version 0.02.32
 * @version $Id$
 * @copyright Copyright (c) 2007, Marcin Sztolcman
 * @license http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)
 * @package blipapi_tools
 */

error_reporting (E_ALL|E_STRICT|E_DEPRECATED);
ini_set ('display_errors', true);

date_default_timezone_set ('Europe/Warsaw');

function import_const () {
    $fh = fopen (ROOT.'/tools/app_data', 'r');
    while (!feof ($fh)) {
        $resp = trim (fgets ($fh, 1024));
        if (!$resp || $resp[0] == '#') {
            continue;
        }
        $resp = explode ('=', $resp, 2);
        define (strtoupper (trim ($resp[0])), trim ($resp[1]));
    }
    fclose ($fh);
}

function blipapi_error_handler ($errno, $error, $errfile=null, $errline=null, $errctx=null) {
    static $errorlevels = array(
        E_ALL               => 'E_ALL',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_NOTICE            => 'E_NOTICE',
        E_PARSE             => 'E_PARSE',
        E_WARNING           => 'E_WARNING',
        E_ERROR             => 'E_ERROR',
    );

    echo getenv ('CYAN')."[". $errorlevels[$errno] .":$errno]\n". getenv ('RED').$error.getenv ('NORM')."\n\n";
    if ($errfile) {
        echo 'File: '. getenv ('GREEN').$errfile.getenv ('NORM')."\n";
    }
    if (!is_null ($errline)) {
        echo 'Line: '. getenv ('GREEN').$errline.getenv ('NORM')."\n";
    }
    if ($errctx) {
        echo "Environment:\n";
        ## nie chcemy tu superglobali
        unset (
            $errctx['_REQUEST'],
            $errctx['_COOKIE'],
            $errctx['_ENV'],
            $errctx['_FILES'],
            $errctx['_GET'],
            $errctx['_POST'],
            $errctx['_SERVER'],
            $errctx['HTTP_COOKIE_VARS'],
            $errctx['HTTP_ENV_VARS'],
            $errctx['HTTP_POST_FILES'],
            $errctx['HTTP_GET_VARS'],
            $errctx['HTTP_POST_VARS'],
            $errctx['HTTP_SERVER_VARS']
        );
        print_r ($errctx);
    }
    echo "\n";

    exit;
}
set_error_handler ('blipapi_error_handler');

