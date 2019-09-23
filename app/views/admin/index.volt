{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="settings__wrapper">
                    {{ partial('common/sessionMessages', [
                        'successMessages': successMessages,
                        'errorMessages': errorMessages
                    ]) }}

                    <form action="{{ url(['for': 'settings-create-update']) }}" method="POST">
                        <div class="row">
                            <div class="col">
                                <label for="exampleInputEmail1">Start of working day</label>
                                <input type="time" class="form-control" id="exampleInputEmail1" name="settings[beginning]"
                                       step="1" value="{{ beginning }}">
                            </div>

                            <div class="col">
                                <label for="exampleInputPassword1">Maximum late for month</label>
                                <input type="text" class="form-control" id="exampleInputPassword1" name="settings[max_late]"
                                       value="{{ maxLate }}">
                            </div>

                            <div class="settings_dtn_wrapper">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{ partial('common/filter', [
                    'action': url(['for': 'admin-index']),
                    'months': months,
                    'years': years,
                    'defaultMonth': defaultMonth,
                    'defaultYear': defaultYear
                ]) }}

                <div class="table__wrapper">
                    {{ partial('common/staffTable', [
                        'users': users,
                        'datesMonth': datesMonth,
                        'currentDate': currentDate,
                        'authUser': authUser
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

                        if (parsedData.validation.length) {
                            var messages = '';

                            $.each(parsedData.validation, function(key, value) {
                                messages = messages + value + '\n';
                            });

                            alert(messages);

                        } else {

                            var userTotalClass = 'user-total_' + parsedData.hourId;
                            var userLessClass = 'user-less_' + parsedData.hourId;

                            if (parsedData.action === 'create') {

                                var total = '<span class="total-hour ' + userTotalClass + '">' + parsedData.total + '</span>';
                                var less = '';

                                if(parsedData.less) {
                                    less = '<span class="less-hour ' + userLessClass + '">' + parsedData.less + '</span>';
                                }

                                if(parsedData.late === 1) {
                                    form.parent().parent().parent().css('background-color', '#ffb9b2');
                                } else {
                                    form.parent().parent().parent().css('background-color', 'rgba(0,0,0, 0)');
                                }

                                form.parent().next().html( total + less );
                                form.attr('action', parsedData.formAction);
                            } else {

                                $('.' + userTotalClass).html('total: ' + parsedData.assignment.total);

                                if (parsedData.assignment.late === 1) {

                                    form.parent().parent().parent().css('background-color', '#ffb9b2');
                                } else {

                                    form.parent().parent().parent().css('background-color', 'rgba(0,0,0, 0)');
                                }

                                if (parsedData.assignment.less) {
                                    $('.' + userLessClass).html('less: ' + parsedData.assignment.less);
                                } else {
                                    $('.' + userLessClass).html('');
                                }
                            }
                        }
                    }
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        });
    });
</script>