<?php
$schemetype = $this->getConfig('scheme');
	if (rex_addon::get('yrewrite')->isAvailable()) {
	    $scheme = new $schemetype();
	    $scheme->setSuffix(rex_config::get('yrewrite_scheme', 'suffix'));
	    $scheme = rex_yrewrite::setScheme($scheme);
	}
