<?php
$content = '';
$buttons = '';
// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1') {
	
    $this->setConfig(rex_post('config', [
        ['suffix', 'string'],
        ['scheme', 'string'],
    ]));
    echo rex_view::success($this->i18n('config_saved'));
    rex_delete_cache();
$newURL = rex_url::currentBackendPage();
// Umleitung auf die aktuelle Seite auslösen
rex_response::sendRedirect($newURL);

}

// BESTIMMUNG DES SUFFIX

$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-suffix">' . $this->i18n('suffix') . '</label>';
$select = new rex_select();
$select->setId('rex-urlreplacer-suffix');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[suffix]');
$select->addOption($this->i18n('oSuf'), Null);
$select->addOption($this->i18n('hSuf'), '.html');
$select->addOption($this->i18n('zSuf'), '/');

$select->setSelected($this->getConfig('suffix'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// BESTIMMUNG DER METHODE

$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-scheme">' . $this->i18n('scheme') . '</label>';
$select = new rex_select();
$select->setId('rex-urlreplacer-scheme');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[scheme]');
$select->addOption($this->i18n('standard'), 'standard');
$select->addOption('URLReplace Variante 1', 'urlreplleer');
$select->addOption('URLReplace Variante 2', 'nomatterurlrepl');
$select->addOption('One-level', 'one_level');

$select->setSelected($this->getConfig('scheme'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Save-Button
$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $this->i18n('config_save') . '">' . $this->i18n('config_save') . '</button>';
$formElements[] = $n;
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');
$buttons = '
<fieldset class="rex-form-action">
    ' . $buttons . '
</fieldset>
';
// Ausgabe Formular
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('config'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$output = $fragment->parse('core/page/section.php');
$output = '
<form action="' . rex_url::currentBackendPage() . '" method="post">
<input type="hidden" name="formsubmit" value="1" />
    ' . $output . '
</form>
';


echo $output;
// Ausgabe Hilfe
$file = rex_file::get(rex_path::addon('schemes','README.md'));
$body = rex_markdown::factory()->parse($file);
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('help'));
$fragment->setVar('body', $body, false);
$content = $fragment->parse('core/page/section.php');
echo $content;
?>