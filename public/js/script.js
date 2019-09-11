$(document).ready(function () {
    $('#hide-show').on('click', function () {
        $('.full_day').each(function () {
            $(this).toggleClass('not_current_working_line');
        });
    });

    function startUpdatingInterval() {
        setInterval(updateWorkingHours, 5000, null, null);
    }

    function initializeStartAndStop() {

        $('.update-hours').on('click', function (e) {
            e.preventDefault();

            var element = $(this);
            var updateActions = {};

            if(element.attr('name') === 'start') {
                updateActions['start'] = 1;
                setTimeout(startUpdatingInterval, 5000);
            }

            if(element.attr('name') === 'stop') {
                updateActions['end'] = 1;
            }

            updateWorkingHours(updateActions, element);
        });
    }

    function updateWorkingHours(updateActions, element) {

        if (updateActions === null) {
            updateActions = {'update': 1};
        }

        var url = $('#update-hours-link').val();

        $.ajax({
            type: 'POST',
            url: url,
            data: updateActions,
            beforeSend: function() {
                if(element !== null) {
                    element.prop('disabled', true);
                }
            },
            success: function (data) {
                $('.working_table_list').html(data);
                initializeStartAndStop();
            },
            error: function (errors) {
                alert(errors.status + ' ' + errors.statusText);
            }
        });
    }

    initializeStartAndStop();
});