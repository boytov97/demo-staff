$(document).ready(function () {

    function isJson(data) {
        try {
            $.parseJSON(data);
        } catch (e) {
            return false;
        }
        return true;
    }

    function initializeStartAndStop() {
        $('.update-hours').off('click').on('click', function (e) {
            e.preventDefault();

            var element = $(this);
            var updateActions = {};

            if (element.attr('name') === 'start') {
                updateActions['action'] = 'start';

                var user_less = '.user-less_';

                $.each(element.attr('data-href').split('/'), function (key, value) {
                    console.log(key + '-----' + value);

                    if (key === 3 && $.isNumeric(value)) {
                        user_less += value;
                    }
                });

                $(user_less).html('');
            }

            if (element.attr('name') === 'stop') {
                updateActions['action'] = 'stop';
            }

            updateWorkingHours(updateActions, element);
        });
    }

    function updateWorkingHours(updateActions, element) {

        $.ajax({
            type: 'POST',
            url: element.attr('data-href'),
            data: updateActions,
            beforeSend: function() {
                if(element !== null) {
                    element.prop('disabled', true);
                }
            },
            success: function (data) {

                if (isJson(data)) {
                    var parsedData = $.parseJSON(data);

                    if (parsedData.success) {
                        location.reload();
                    }
                }

                initializeStartAndStop();
            },
            error: function (errors) {
                alert(errors.status + ' ' + errors.statusText);
            }
        });
    }

    initializeStartAndStop();

    $('.start_end_edit').on('click', function(event) {
        event.preventDefault();

        $(this).parent().children('input').removeClass('input_without_border');
        $(this).parent().children('button').removeClass('hidden_start-end_btn');
        $(this).addClass('hidden_start-end_btn');
    });

    $('.start_end_save').on('click', function(event) {
        event.preventDefault();

        $(this).parent().children('input').addClass('input_without_border');
        $(this).parent().children('a').removeClass('hidden_start-end_btn');
        $(this).addClass('hidden_start-end_btn');

        var form =  $(this).parent();

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            beforeSend: function() {
                $(this).prop('disabled', true);
            },
            success: function (data) {
                if (isJson(data)) {
                    var parsedData = $.parseJSON(data);
                    var userTotalClass = '.user-total_' + parsedData.hourId;
                    var userLessClass = '.user-less_' + parsedData.hourId;

                    $(userTotalClass).html('total: ' + parsedData.entity.total);

                    if (parsedData.entity.less) {
                        $(userLessClass).html('less: ' + parsedData.entity.less);
                    } else {
                        $(userLessClass).html('');
                    }
                }
            },
            error: function (errors) {
                alert(errors.status + ' ' + errors.statusText);
            }
        });
    });
});
