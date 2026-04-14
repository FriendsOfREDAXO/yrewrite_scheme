<?php
if(rex_addon::get('yrewrite')->isAvailable()) {
	$scheme = new yrewrite_url_schemes();
	$scheme->setSuffix(rex_config::get('yrewrite_scheme', 'suffix'));
	rex_yrewrite::setScheme($scheme);
}

if (rex::isBackend() && rex::getUser() && rex_be_controller::getCurrentPage() === 'yrewrite/yrewrite_scheme') {
	rex_view::addJsFile(rex_addon::get('yrewrite_scheme')->getAssetsUrl('yrewrite_scheme.js'));
}