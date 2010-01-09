<?php

require_once ROOT.'/tools/Tests/Abstract.php';
require_once ROOT.'/lib/OAuth.php';
require_once ROOT.'/tools/lib.php';

class TestsAbstractLogged extends TestsAbstract {
    public static function setUpBeforeClass () {
        import_const ();
    }

    public function setUp () {
        $C = new OAuthConsumer (CONSUMER_KEY, CONSUMER_SECRET);
        $T = new OAuthToken (TOKEN_KEY, TOKEN_SECRET);
        $this->B = new BlipApi ($C, $T);
    }

}

