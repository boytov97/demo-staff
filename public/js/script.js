$(document).ready(function () {
    $('#hide-show').on('click', function () {
        $('.full_day').each(function () {
            $(this).toggleClass('not_current_working_line');
        });
    });

    function sendStartAndStop() {
        $('#start-time').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('href'),
                data: {'start': 1},
                beforeSend: function() {
                    $('#start-time').prop('disabled', true);
                    $('#start-time').val('starting...');
                },
                success: function (data) {
                    $('.working_table_list').html(data);
                    sendStartAndStop();
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        });

        $('#stop-time').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('href'),
                data: {'start': 1, 'end': 1},
                beforeSend: function() {
                    $('#stop-time').prop('disabled', true);
                    $('#stop-time').val('starting...');
                },
                success: function (data) {
                    $('.working_table_list').html(data);
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        });
    }

    sendStartAndStop();
});