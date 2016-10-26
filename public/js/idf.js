
$(function() {
	
    var $componentTable = $('table#componentTable'),
        $componentCheckboxes = $componentTable.find('input[type=checkbox]'),
        $toggleAll = $('#toggleAll'),
        $lastClickedRow = null,
        multiSelect = false,
        $document = $(document);

	// Make components table sortable.
    $componentTable.tablesorter({
		headers: {
			0: {
				sorter: false
			}
		}
	});

    $toggleAll.change(function () {
        var $checkbox = $(this);
        var checked = $checkbox.prop('checked') ? 1 : 0;

        // Update all component checkboxes to match the toggle-all checkbox.
        $componentTable.find('input[type=checkbox]').prop('checked', checked).trigger('change');
    });

    $componentCheckboxes.change(function () {
        var $checkbox = $(this),
            checked = $checkbox.prop('checked') ? 1 : 0,
            $row = $checkbox.parents('tr');

        // Update row highlighting.
        if (checked) {
            $row.addClass('selected');
        } else {
            $row.removeClass('selected');
        }

        if (multiSelect) {
            $lastClickedRow.nextUntil($row).find('input[type=checkbox]')
                .prop('checked', checked)
                .trigger('change');
        }

        $lastClickedRow = $row;
    });

    $document.keydown(function(event) {
        multiSelect = event.shiftKey || event.ctrlKey;
    });

    $document.keyup(function() {
        multiSelect = false;
    });
});
