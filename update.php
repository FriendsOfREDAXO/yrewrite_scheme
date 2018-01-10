<?php 
if (rex_string::versionCompare($this->getVersion(), '2.0', '<')) {
    $this->setConfig('suffix', '/');
    $this->setConfig('scheme', 'yrewrite_scheme_suffix');
}

