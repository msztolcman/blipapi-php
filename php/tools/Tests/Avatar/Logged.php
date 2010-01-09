<?php

require_once 'PHPUnit/Framework.php';
define ('ROOT', dirname (__FILE__).'/..');
require_once ROOT.'/lib/blipapi.php';

class TestAvatarNotLogged extends  PHPUnit_Framework_TestCase {
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

    public function testOther () {
        $o = new BlipApi_Avatar ();
        $o->user = 'mysz';

        try {
            $R = $this->B->read ($o);
            $this->validateResponse ($R);
        }
        catch (RuntimeException $e) {
            $this->fail ('unxpected RuntimeException: '. $e->getMessage ());
            return;
        }

        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $R['body']);
        $this->assertObjectHasAttribute ('url', $R['body']);
        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $R['body']->url);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testReadOwn () {
        $o = new BlipApi_Avatar ();

        $R = $this->B->read ($o);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUpdate () {
        $o = new BlipApi_Avatar ();
        $o->image = ROOT.'/tools/imgs/avatar.jpg';

        $R = $this->B->update ($o);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testDelete () {
        $o = new BlipApi_Avatar ();

        $R = $this->B->delete ($o);
    }
}

?>
