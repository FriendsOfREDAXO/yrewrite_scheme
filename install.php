<?php
if (!$this->hasConfig()) {
	$this->setConfig('suffix', '/');
	$this->setConfig('scheme', 'yrewrite_scheme_suffix');
	$this->setConfig('urlreplacer', '');
	$this->setConfig('excluded_categories',[]);
}
