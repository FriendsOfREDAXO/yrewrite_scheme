<?php
if(rex_addon::get('yrewrite')->isAvailable()) {
	$scheme = new yrewrite_url_schemes();
	$scheme->setSuffix(rex_config::get('yrewrite_scheme', 'suffix'));
	rex_yrewrite::setScheme($scheme);
}