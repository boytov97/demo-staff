$(document).ready(function () {
    $('#hide-show').on('click', function () {
        $('.full_day').each(function () {
            $(this).toggleClass('not_current_working_line');
        });
    });

    function startUpdateInterval() {
        setInterval(updateTotal, 5000);
    }

    function initializeStartAndStop() {

        $('.update-hours').on('click', function (e) {
            e.preventDefault();

            var element = $(this);
            var updateActions = {};

            if(element.attr('name') === 'start') {
                updateActions['action'] = 'start';

                startUpdateInterval();
            }

            if(element.attr('name') === 'stop') {
                updateActions['action'] = 'stop';
            }

            updateWorkingHours(updateActions, element);
        });
    }

    function updateWorkingHours(updateActions, element) {

        if (element !== null) {
            var url = element.attr('href');
        }

        if (updateActions === null) {
            updateActions = {'action': 'update'};
            url = $('#update-hours-link').val();
        }

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
                var parsedData = $.parseJSON(data);

                $.each(parsedData.startEnds, function(key, value) {
                    var startEnd = '.start-end_' + value.id;

                    if(value.end === null && value.start !== null) {
                        $(startEnd).find('a').attr('name', 'stop').html('stop');

                        $(startEnd).html(value.start + ' - ' + $(startEnd).html());
                    } else {
                        if(value.end === null && value.start === null && parsedData.urlForNewStartEnd !== null) {
                            $('.new-startEnd').last().append('<span class="start-end_'+ value.id +'"><a href="' + parsedData.urlForNewStartEnd + '" name="start" class="update-hours" >start</a></span>');
                        } else {
                            $(startEnd).html(value.start + ' - ' + value.end);

                            if (parsedData.startEnds.length - 2 === key && parsedData.action === 'stop') {
                                $(startEnd).parent().after('<center class="new-startEnd"></center>');
                            }
                        }
                    }

                    $('.auth-user-total').html('total: ' + parsedData.total);
                });

                initializeStartAndStop();
            },
            error: function (errors) {
                alert(errors.status + ' ' + errors.statusText);
            }
        });
    }

    function updateTotal() {

        var url = $('#update-hours-link').val();

        $.ajax({
            type: 'POST',
            url: url,
            data: {},
            success: function (data) {
                var parsedData = $.parseJSON(data);

                $('.auth-user-total').html('total: ' + parsedData.total);
            },
            error: function (errors) {
                alert(errors.status + ' ' + errors.statusText);
            }
        });
    }

    initializeStartAndStop();
});