<?php

require_once ROOT.'/tools/Tests/Abstract.php';

class TestsAbstractNotLogged extends TestsAbstract {
    public function setUp () {
        $this->B = new BlipApi ();
    }

}

