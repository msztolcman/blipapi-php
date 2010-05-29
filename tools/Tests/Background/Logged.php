<?php

define ('ROOT', dirname (__FILE__).'/../../..');
require_once ROOT.'/tools/Tests/AbstractLogged.php';

class TestBackgroundLogged extends TestsAbstractLogged {
    public function testUpdate () {
        $o = new BlipApi_Background ();
        $o->image = ROOT.'/tools/imgs/01.jpg';

        try {
            $R = $this->B->update ($o);
            $this->validateResponse ($R, 201, 'given: '.$R['status_code']);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '.$e->getMessage ());
        }
    }

    public function testReadOther () {
        $o = new BlipApi_Background ();
        $o->user = 'mysz';

        try {
            $R = $this->B->read ($o);
            $this->validateResponse ($R, null, 'given: '.$R['status_code']);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '. $e->getMessage ());
            return;
        }

        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $R['body']);
        $this->assertObjectHasAttribute ('url', $R['body']);
        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $R['body']->url);
        $this->assertStringStartsWith ('http://blip.pl/user_generated/backgrounds/', $R['body']->url, 'given: '. $R['body']->url);
    }

    /**
     * @depends testUpdate
     */
    public function testReadOwn () {
        $this->markTestSkipped ('obecnie blip nie pozwala na odczyt backgrund bez podania usera');
        $o = new BlipApi_Background ();

        try {
            $R = $this->B->read ($o);
            $this->validateResponse ($R);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '. $e->getMessage ());
            return;
        }

        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $R['body']);
        $this->assertObjectHasAttribute ('url', $R['body']);
        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $R['body']->url);
        $this->assertStringStartsWith ('http://blip.pl/user_generated/backgrounds/', $R['body']->url, 'given: '. $R['body']->url);
    }

    /**
     * @depends testUpdate
     */
    public function testDelete () {
        $o = new BlipApi_Background ();

        try {
            $R = $this->B->delete ($o);
            $this->validateResponse ($R);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '.$e->getMessage ());
        }
    }
}

?>
