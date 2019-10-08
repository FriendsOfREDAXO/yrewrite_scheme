<?php
// Version-Check	
if (rex_string::versionCompare($this->getVersion(), '2.0', '<')) {
	if($this->getConfig('scheme') == 'standard') {
		$this->setConfig('scheme', 'yrewrite_scheme_suffix');
	}

	if($this->getConfig('scheme') == 'nomatterurlrepl') {
		$this->setConfig('scheme', 'yrewrite_scheme_nomatter');
	}

	if($this->getConfig('scheme') == 'urlreplleer') {
		$this->setConfig('scheme', 'yrewrite_scheme_urlreplace');
	}

	if($this->getConfig('scheme') == 'one_level') {
		$this->setConfig('scheme', 'yrewrite_one_level');
	}
}
if (rex_string::versionCompare($this->getVersion(), '3.0', '<')) {
	if($this->getConfig('scheme') == 'yrewrite_classic_mode') {
	$this->setConfig('scheme', 'standard');
	}
	
}
if (rex_string::versionCompare($this->getVersion(), '3.1', '<')) {
	if($this->getConfig('scheme') == 'rewrite_scheme_urlreplacer') {
	$this->setConfig('scheme', 'standard');
        $this->setConfig('urlreplacer', 'urlreplace');
	}
	if($this->getConfig('scheme') == 'yrewrite_scheme_nomatter') {
	$this->setConfig('scheme', 'standard');
        $this->setConfig('urlreplacer', 'nomatter');
	}	
}
