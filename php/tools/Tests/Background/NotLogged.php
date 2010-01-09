<?php

require_once 'PHPUnit/Framework.php';
define ('ROOT', dirname (__FILE__).'/..');
require_once ROOT.'/lib/blipapi.php';

class TestBackgroundNotLogged extends  PHPUnit_Framework_TestCase {
    protected $B;

    public function setUp () {
        $this->B = new BlipApi ();
    }

    public function tearDown () {
        $this->B = null;
    }

    private function validateResponse ($R, $code=200) {
        $this->assertEquals ($R['status_code'], $code);
    }

    public function testReadOwn () {
        $o = new BlipApi_Background ();

        $R = $this->B->read ($o);
        $this->validateResponse ($R);

        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $R['body']);
        $this->assertObjectHasAttribute ('url', $R['body']);
        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $R['body']->url);
    }

    public function testReadOther () {
        $o = new BlipApi_Background ();
        $o->user = 'mysz';

        $R = $this->B->read ($o);
        $this->validateResponse ($R);

        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $R['body']);
        $this->assertObjectHasAttribute ('url', $R['body']);
        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $R['body']->url);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUpdate () {
        $o = new BlipApi_Background ();
        $o->image = ROOT.'/tools/imgs/01.jpg';

        $R = $this->B->update ($o);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testDelete () {
        $o = new BlipApi_Background ();

        $R = $this->B->delete ($o);
    }
}

?>
