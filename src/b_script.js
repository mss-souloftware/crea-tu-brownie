(function ($) {
    $(document).ready(function ($) {
        $("#disable_dates").flatpickr({
            mode: "multiple",
            dateFormat: "Y-m-d",
        });

        $("#disable_days_range").flatpickr({
            mode: "range",
            dateFormat: "Y-m-d",
        });


        // Select or Deselect all checkboxes
        $('#selectAll').change(function () {
            var isChecked = $(this).prop('checked');
            $('.AdministracionVentas-table-tbody input[type="checkbox"]').prop('checked', isChecked);
        });

        // Delete selected rows
        $('#deleteAll').click(function () {
            var selectedIds = [];
            $('.AdministracionVentas-table-tbody ul li input[type="checkbox"]:checked').each(function () {
                selectedIds.push($(this).val());
                console.log(selectedIds);
            });

            if (selectedIds.length > 0) {
                $.ajax({
                    url: calendarSettings.ajax_url, // WordPress AJAX URL
                    type: 'POST',
                    data: {
                        datatype: 'JSON',
                        action: 'delete_rows',
                        ids: selectedIds
                    },
                    success: function (response) {
                        if (response.success) {
                            // Remove the deleted rows from the table
                            selectedIds.forEach(function (id) {
                                $('#openPannel_' + id).remove();
                            });
                            alert('Selected rows have been deleted.');
                            location.reload();
                        } else {
                            alert('Failed to delete selected rows.');
                        }
                    },
                    error: function () {
                        alert('Error occurred while deleting rows.');
                    }
                });
            } else {
                alert('No rows selected.');
            }
        });

    });
}(jQuery))