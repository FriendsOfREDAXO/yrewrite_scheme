<?php
if($this->getConfig('scheme') == 'standard') {
	if (rex_addon::get('yrewrite')->isAvailable()) {
	    rex_yrewrite::setScheme(new yrewrite_scheme_suffix);
	}
}
if($this->getConfig('scheme') == 'nomatterurlrepl') {
	if (rex_addon::get('yrewrite')->isAvailable()) {
	    rex_yrewrite::setScheme(new yrewrite_scheme_nomatter);
	}
}
if($this->getConfig('scheme') == 'urlreplleer') {
	if (rex_addon::get('yrewrite')->isAvailable()) {
	    rex_yrewrite::setScheme(new yrewrite_scheme_urlreplace());
	}
}
if($this->getConfig('scheme') == 'one_level') {
	if (rex_addon::get('yrewrite')->isAvailable()) {
	    rex_yrewrite::setScheme(new yrewrite_one_level());
	}
}
