<?php

require_once 'PHPUnit/Framework.php';
define ('ROOT', dirname (__FILE__).'/..');
require_once ROOT.'/lib/blipapi.php';
require_once ROOT.'/lib/OAuth.php';
require_once ROOT.'/tools/lib.php';

class TestBackgroundLogged extends  PHPUnit_Framework_TestCase {
    protected $B;

    public static function setUpBeforeClass () {
        import_const ();
    }

    public function setUp () {
        $C = new OAuthConsumer (CONSUMER_KEY, CONSUMER_SECRET);
        $T = new OAuthToken (TOKEN_KEY, TOKEN_SECRET);
        $this->B = new BlipApi ($C, $T);
    }

    public function tearDown () {
        $this->B = null;
    }

    private function validateResponse ($R, $code=200) {
        $this->assertEquals ($R['status_code'], $code);
    }

    public function testUpdate () {
        $o = new BlipApi_Background ();
        $o->image = ROOT.'/tools/imgs/01.jpg';

        try {
            $R = $this->B->update ($o);
            $this->validateResponse ($R);
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
            $this->validateResponse ($R);
        }
        catch (RuntimeException $e) {
            $this->fail ('unexpected RuntimeException: '. $e->getMessage ());
            return;
        }

        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $R['body']);
        $this->assertObjectHasAttribute ('url', $R['body']);
        $this->assertType (PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $R['body']->url);
        $this->assertStringStartsWith ('http://blip.pl/user_generated/backgrounds/', $R['body']->url);
    }

    /**
     * @depends testUpdate
     */
    public function testReadOwn () {
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
        $this->assertStringStartsWith ('http://blip.pl/user_generated/backgrounds/', $R['body']->url);
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
