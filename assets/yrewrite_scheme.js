$(document).on('rex:ready', function () {
    // Neue Ersetzungszeile hinzufügen
    $(document).on('click', '.add-replace', function () {
        var $row = $(this).closest('tr');
        var $table = $row.closest('table');
        var langId = $table.closest('.language-replaces').data('lang-id');
        var rowCount = $table.find('tbody tr').length;

        var $newRow = $('<tr></tr>');
        $newRow.append('<td><input type="text" class="form-control" name="config[language_replaces_' + langId + '][' + rowCount + '][search]"></td>');
        $newRow.append('<td><input type="text" class="form-control" name="config[language_replaces_' + langId + '][' + rowCount + '][replace]"></td>');
        $newRow.append('<td><button type="button" class="btn btn-danger remove-replace">-</button></td>');

        $row.before($newRow);
    });

    // Ersetzungszeile entfernen
    $(document).on('click', '.remove-replace', function () {
        $(this).closest('tr').remove();
    });
});
