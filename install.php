<?php

if (!$this->hasConfig()) {
	$this->setConfig('suffix', '/');
	$this->setConfig('scheme', 'standard');


}

$somethingIsWrong = false;
if ($somethingIsWrong) {
    throw new rex_functional_exception('Something is wrong');
}

if ($somethingIsWrong) {
    $this->setProperty('installmsg', 'Something is wrong');
    $this->setProperty('install', false);
}
?>