<?php

define ('ROOT', dirname (__FILE__).'/../../..');
require_once ROOT.'/tools/Tests/AbstractLogged.php';

class TestAvatarLogged extends TestsAbstractLogged {
    public function testUpdate () {
        $o = new BlipApi_Avatar ();
        $o->image = ROOT.'/tools/imgs/avatar.jpg';

        try {
            $R = $this->B->update ($o);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '.$e->getMessage ());
        }
    }

    public function testReadOther () {
        $o = new BlipApi_Avatar ();
        $o->user = 'mysz';

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
        $this->assertStringStartsWith ('http://blip.pl/user_generated/avatars/', $R['body']->url);
    }

    /**
     * @depends testUpdate
     */
    public function testReadOwn () {
        $this->markTestSkipped ('blip nie pozwala aktualnie na pobranie wlasnego avatara (bez podania usera)');
        $o = new BlipApi_Avatar ();

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
        $this->assertStringStartsWith ('http://blip.pl/user_generated/avatars/', $R['body']->url);
    }

    /**
     * @depends testUpdate
     */
    public function testDelete () {
        $o = new BlipApi_Avatar ();

        try {
            $R = $this->B->delete ($o);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '.$e->getMessage ());
        }
    }
}

?>
