// Delegierte Event-Handler außerhalb von rex:ready registrieren,
// damit sie bei PJAX-Navigation nicht mehrfach gebunden werden.
$(document)
    .off('click.yrewriteScheme', '.add-replace')
    .on('click.yrewriteScheme', '.add-replace', function () {
        var $row = $(this).closest('tr');
        var $table = $row.closest('table');
        var langId = $table.closest('.language-replaces').data('lang-id');

        // Höchsten vorhandenen Index ermitteln, um Kollisionen beim Löschen zu vermeiden
        var maxIndex = -1;
        $table.find('tbody input[name]').each(function () {
            var match = $(this).attr('name').match(/^config\[language_replaces_[^\]]+\]\[(\d+)\]/);
            if (match) {
                maxIndex = Math.max(maxIndex, parseInt(match[1], 10));
            }
        });
        var nextIndex = maxIndex + 1;

        var $newRow = $('<tr></tr>');
        $newRow.append('<td><input type="text" class="form-control" name="config[language_replaces_' + langId + '][' + nextIndex + '][search]"></td>');
        $newRow.append('<td><input type="text" class="form-control" name="config[language_replaces_' + langId + '][' + nextIndex + '][replace]"></td>');
        $newRow.append('<td><button type="button" class="btn btn-danger remove-replace">-</button></td>');

        $row.before($newRow);
    })
    .off('click.yrewriteScheme', '.remove-replace')
    .on('click.yrewriteScheme', '.remove-replace', function () {
        $(this).closest('tr').remove();
    });
