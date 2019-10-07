<?php
$addon = rex_addon::get('yrewrite_scheme');
$content = '';
$buttons = '';

// save settings
if (rex_post('formsubmit', 'string') == '1') {
	$configs = [
        ['suffix', 'string'],
        ['scheme', 'string'],
    ];
	foreach(rex_clang::getAll() as $rex_clang) {
		$configs[] = ['urlencode-lang-' . $rex_clang->getId(), 'string'];
	}
    $addon->setConfig(rex_post('config', $configs));

	echo rex_view::success($addon->i18n('config_saved'));
    
	if (rex::getVersion() == "5.6.0") {
       rex_config::save(); // REX 5.6.0 Save Fix
    }
    rex_delete_cache();
}

// rewrite suffix
$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-suffix">' . $addon->i18n('suffix') . '</label>';
$select = new rex_select();
$select->setId('rex-urlreplacer-suffix');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[suffix]');
$select->addOption($addon->i18n('oSuf'), Null);
$select->addOption($addon->i18n('hSuf'), '.html');
$select->addOption($addon->i18n('zSuf'), '/');

$select->setSelected($addon->getConfig('suffix'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// rewrite method
$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-scheme">' . $addon->i18n('scheme') . '</label>';
$select = new rex_select();
$select->setId('rex-urlreplacer-scheme');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[scheme]');
$select->addOption($addon->i18n('standard'), 'yrewrite_scheme_suffix');
$select->addOption($addon->i18n('urlreplace_var_1'), 'yrewrite_scheme_urlreplace');
$select->addOption($addon->i18n('urlreplace_var_2'), 'yrewrite_scheme_nomatter');
$select->addOption($addon->i18n('yrewrite_scheme_one_level'), 'yrewrite_one_level');
$select->addOption($addon->i18n('yrewrite_scheme_classic'), 'yrewrite_classic_mode');

$select->setSelected($addon->getConfig('scheme'));
$n['field'] = $select->get();
$formElements[] = $n;

// language specific urlencode settings
foreach(rex_clang::getAll() as $rex_clang) {
	$n['label'] = '<label for="rex-urlreplacer-scheme-lang-' . $rex_clang->getId() . '">' . $rex_clang->getName() . '</label>';
	$select = new rex_select();
	$select->setId('rex-urlencode-lang-' . $rex_clang->getId());
	$select->setAttribute('class', 'form-control selectpicker');
	$select->setName('config[urlencode-lang-' . $rex_clang->getId() . ']');
	$select->addOption(rex_i18n::msg('yrewrite_scheme_yrewrite'), 'standard');
	$select->addOption(rex_i18n::msg('yrewrite_scheme_urlencode'), 'urlencode');

	$select->setSelected($addon->getConfig('urlencode-lang-' . $rex_clang->getId()));
	$n['field'] = $select->get();
	$formElements[] = $n;
}

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// save-Button
$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $addon->i18n('config_save') . '">' . $addon->i18n('config_save') . '</button>';
$formElements[] = $n;
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');
$buttons = '
<fieldset class="rex-form-action">
    ' . $buttons . '
</fieldset>
';

// print form
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $addon->i18n('settings'));
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

// print help
$file = rex_file::get(rex_path::addon('yrewrite_scheme','README.md'));
$body = rex_markdown::factory()->parse($file);
$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('credits_help'));
$fragment->setVar('body', $body, false);
$content = $fragment->parse('core/page/section.php');
echo $content;