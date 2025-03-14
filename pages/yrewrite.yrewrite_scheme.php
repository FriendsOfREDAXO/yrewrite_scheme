<?php
$addon = rex_addon::get('yrewrite_scheme');
$content = '';
$buttons = '';

// save settings
if (rex_post('formsubmit', 'string') == '1') {
    $configs = [
        ['suffix', 'string'],
        ['scheme', 'string'],
        ['urlreplacer', 'string'],
        ['excluded_categories', 'array'],
    ];
    
    // Sprachspezifische Ersetzungen speichern
    foreach (rex_clang::getAll() as $rex_clang) {
        $clang_id = $rex_clang->getId();
        $configs[] = ['urlencode-lang-' . $clang_id, 'string'];
        
        // Sprachspezifische Ersetzungen aus dem POST-Array lesen
        $language_replaces = [];
        if (isset($_POST['config']['language_replaces_' . $clang_id]) && is_array($_POST['config']['language_replaces_' . $clang_id])) {
            foreach ($_POST['config']['language_replaces_' . $clang_id] as $index => $item) {
                if (!empty($item['search']) && !empty($item['replace'])) {
                    $language_replaces[] = [
                        'search' => $item['search'],
                        'replace' => $item['replace']
                    ];
                }
            }
        }
        $addon->setConfig('language_replaces_' . $clang_id, $language_replaces);
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
$n['label'] = '<label for="rex-url-scheme">' . $addon->i18n('scheme') . '</label>';
$select = new rex_select();
$select->setId('rex-url-scheme');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[scheme]');
$select->addOption($addon->i18n('standard'), 'yrewrite_scheme_suffix');
$select->addOption($addon->i18n('yrewrite_scheme_one_level'), 'yrewrite_one_level');

$select->setSelected($addon->getConfig('scheme'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');


// exclude catgeories
$tableSelect = new rex_category_select($ignore_offlines = false, $clang = false,  $check_perms = false, $add_homepage = true);
$tableSelect->setName('config[excluded_categories][]');
$tableSelect->setId('rex-exclude-categories');
$tableSelect->setMultiple();
$tableSelect->setSelected($addon->getConfig('excluded_categories'));
$tableSelect->setAttribute('class', 'form-control selectpicker ');
$tableSelect->setAttribute('data-live-search', 'true');
$tableSelect->setSize(10);


$formElements = [];
$n = [];
$n['label'] = '<label for="rex-exclude-categories">' . $addon->i18n('categories') . '</label>';
$n['field'] = $tableSelect->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');


$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-urlreplacer">' . $addon->i18n('urlreplace') . '</label>';
$select = new rex_select();
$select->setId('rex-url-replacer');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[urlreplacer]');
$select->addOption($addon->i18n('urlreplace_disabled'), '');
$select->addOption($addon->i18n('urlreplace_var_1'), 'yrewrite_scheme_urlreplace');
$select->addOption($addon->i18n('urlreplace_var_2'), 'yrewrite_scheme_nomatter');

$select->setSelected($addon->getConfig('urlreplacer'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

$formElements = [];
$n = [];
// language specific urlencode settings
foreach(rex_clang::getAll() as $rex_clang) {
    $n['label'] = '<label for="rex-urlreplacer-scheme-lang-' . $rex_clang->getId() . '">' . $rex_clang->getName() . '</label>';
    $select = new rex_select();
    $select->setId('rex-urlencode-lang-' . $rex_clang->getId());
    $select->setAttribute('class', 'form-control selectpicker');
    $select->setName('config[urlencode-lang-' . $rex_clang->getId() . ']');
    $select->addOption(rex_i18n::msg('yrewrite_scheme_original'), 'original');
    $select->addOption(rex_i18n::msg('yrewrite_scheme_yrewrite'), 'standard');
    $select->addOption(rex_i18n::msg('yrewrite_scheme_urlencode'), 'urlencode');

    $select->setSelected($addon->getConfig('urlencode-lang-' . $rex_clang->getId()));
    $n['field'] = $select->get();
    $formElements[] = $n;
}

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Sprachspezifische Ersetzungen
foreach (rex_clang::getAll() as $rex_clang) {
    $clang_id = $rex_clang->getId();
    $lang_name = $rex_clang->getName();
    
    // Bestehende Ersetzungen holen
    $language_replaces = $addon->getConfig('language_replaces_' . $clang_id, []);
    
    // Formular für sprachspezifische Ersetzungen
    $formContent = '<div class="language-replaces" data-lang-id="' . $clang_id . '">';
    $formContent .= '<table class="table" id="language-replaces-table-' . $clang_id . '">';
    $formContent .= '<thead><tr><th>' . $addon->i18n('search_for') . '</th><th>' . $addon->i18n('replace_with') . '</th><th></th></tr></thead>';
    $formContent .= '<tbody>';
    
    if (!empty($language_replaces)) {
        foreach ($language_replaces as $index => $item) {
            $formContent .= '<tr>';
            $formContent .= '<td><input type="text" class="form-control" name="config[language_replaces_' . $clang_id . '][' . $index . '][search]" value="' . htmlspecialchars($item['search']) . '"></td>';
            $formContent .= '<td><input type="text" class="form-control" name="config[language_replaces_' . $clang_id . '][' . $index . '][replace]" value="' . htmlspecialchars($item['replace']) . '"></td>';
            $formContent .= '<td><button type="button" class="btn btn-danger remove-replace">-</button></td>';
            $formContent .= '</tr>';
        }
    }
    
    // Leere Zeile für neue Einträge
    $formContent .= '<tr>';
    $formContent .= '<td><input type="text" class="form-control" name="config[language_replaces_' . $clang_id . '][' . count($language_replaces) . '][search]" placeholder="&"></td>';
    $formContent .= '<td><input type="text" class="form-control" name="config[language_replaces_' . $clang_id . '][' . count($language_replaces) . '][replace]" placeholder="' . ($lang_name === 'Deutsch' ? 'und' : 'and') . '"></td>';
    $formContent .= '<td><button type="button" class="btn btn-success add-replace">+</button></td>';
    $formContent .= '</tr>';
    
    $formContent .= '</tbody></table>';
    $formContent .= '</div>';
    
    $fragment = new rex_fragment();
    $fragment->setVar('title', $lang_name . ' - ' . $addon->i18n('custom_replacements'));
    $fragment->setVar('body', $formContent, false);
    $content .= $fragment->parse('core/page/section.php');
}

// JavaScript für dynamisches Hinzufügen/Entfernen von Ersetzungszeilen
$content .= '
<script type="text/javascript" nonce="' . rex_response::getNonce() . '">
$(document).ready(function() {
    // Neue Ersetzung hinzufügen
    $(document).on("click", ".add-replace", function() {
        var $row = $(this).closest("tr");
        var $table = $row.closest("table");
        var langId = $table.closest(".language-replaces").data("lang-id");
        var rowCount = $table.find("tbody tr").length;
        
        var $newRow = $("<tr></tr>");
        $newRow.append("<td><input type=\"text\" class=\"form-control\" name=\"config[language_replaces_" + langId + "][" + rowCount + "][search]\"></td>");
        $newRow.append("<td><input type=\"text\" class=\"form-control\" name=\"config[language_replaces_" + langId + "][" + rowCount + "][replace]\"></td>");
        $newRow.append("<td><button type=\"button\" class=\"btn btn-danger remove-replace\">-</button></td>");
        
        $row.before($newRow);
    });
    
    // Ersetzung entfernen
    $(document).on("click", ".remove-replace", function() {
        $(this).closest("tr").remove();
    });
});
</script>';

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
if ( is_readable($addon->getPath('README.'.rex_i18n::getLanguage().'.md')) ) {
    [$readmeToc, $readmeContent] = rex_markdown::factory()->parseWithToc(rex_file::require($addon->getPath('README.'. rex_i18n::getLanguage() .'.md')), 2, 3, false);
} else {
    [$readmeToc, $readmeContent] = rex_markdown::factory()->parseWithToc(rex_file::require($addon->getPath('README.md')), 2, 3, false);
}
$fragment = new rex_fragment();
$fragment->setVar('content', $readmeContent, false);
$fragment->setVar('toc', $readmeToc, false);
$content = $fragment->parse('core/page/docs.php');
$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('credits_help'));
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;
