<?php

require_once ROOT.'/tools/PHPUnit/Framework.php';
require_once ROOT.'/lib/blipapi.php';

class TestsAbstract extends PHPUnit_Framework_TestCase {
    protected $B;

    public function tearDown () {
        $this->B = null;
    }

    protected function validateResponse ($R, $code=200, $msg='') {
        if (is_null ($code)) {
            $code = 200;
        }
        $this->assertEquals ($R['status_code'], $code, $msg);
    }

}

