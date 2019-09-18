{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="row statistics__wrapper">
                <div class="col-md-6 user_statistics__wrapper">
                    <p>Total hours per month: <span class="total_hours_per_month">{{ totalPerMonth }}</span></p>
                    <p>You have/Assigned: <span class="total_hours_per_month">{{ percentOfTotal }}%</span></p>
                    <p>Assigned: <span class="total_hours_per_month">{{ workingHoursCount }}</span></p>
                    <span>Ты опаздал: {{ authUserlateCount ? authUserlateCount : 0 }} раз</span><br>

                    {% if maxLate is defined %}
                        <span>Если общее кол-во опозданий превысит {{ maxLate }}.</span><br>
                        <span>Будут применятся штрафные санкции.</span>

                        <div class="scale_max_late">
                            <div style="width: {{ lateCountPerMonth * maxLate / 100 }}%;" class="late_count_scale">{{ lateCountPerMonth }}</div>
                        </div>
                    {% endif %}
                </div>

                {% if lateUsers is not empty %}
                    <div class="col-md-6 lates_statistics__wrapper">
                        <p>Главные опоздуны</p>

                        {% for lateUser in lateUsers %}
                            <div class="late__card">
                                <div class="late__image__wrapper">
                                    {% if lateUser.image is not empty %}
                                        {{ image(lateUser.image, 'alt': lateUser.name, 'class': 'late__image') }}
                                    {% else %}
                                        {{ image('img/default.jpg', 'alt': lateUser.name, 'class': 'late__image') }}
                                    {% endif %}
                                </div>

                                <br>
                                <p>{{ lateUser.name }}</p>
                                <span>{{ lateUser.beenLate }} pаз</span>
                            </div>
                        {% endfor %}
                    </div>
                {% endif %}
            </div>

            {{ partial('common/filter', [
                'action': url(['for': 'hours-index']),
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

<script>
    $(document).ready(function () {

        var updateTotalInterval;

        function startUpdateInterval() {
            updateTotalInterval = setInterval(updateTotal, 60000);
        }

        function stopUpdateInterval() {
            clearInterval(updateTotalInterval);
        }

        function initializeStartAndStop() {

            $('.update-hours').on('click', function (e) {
                e.preventDefault();

                var element = $(this);
                var updateActions = {};

                if(element.attr('name') === 'start') {
                    updateActions['action'] = 'start';

                    startUpdateInterval();

                    var user_less = '.user-less_';

                    $.each(element.attr('data-href').split('/'), function (key, value) {
                        console.log(key + '-----' + value);

                        if (key === 3 && $.isNumeric(value)) {
                            user_less += value;
                        }
                    });

                    $(user_less).html('');
                }

                if(element.attr('name') === 'stop') {
                    updateActions['action'] = 'stop';

                    stopUpdateInterval();
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
                    var parsedData = $.parseJSON(data);

                    $.each(parsedData.startEnds, function(key, value) {
                        var startEnd = '.start-end_' + value.id;

                        if(value.stop === null && value.start !== null) {
                            $(startEnd).find('a').attr('name', 'stop').html('stop');

                            $(startEnd).html(value.start + ' - ' + $(startEnd).html());
                        } else {
                            if(value.stop === null && value.start === null && parsedData.updateUrl !== null) {
                                $('.new-startEnd').last().append('<span class="start-end_'+ value.id +'"><a data-href="' + parsedData.updateUrl + '" name="start" class="update-hours" >start</a></span>');
                            } else {
                                $(startEnd).html(value.start + ' - ' + value.stop);

                                if (parsedData.startEnds.length - 2 === key && parsedData.action === 'stop') {
                                    $(startEnd).parent().after('<center class="new-startEnd"></center>');
                                }
                            }
                        }

                        if (parsedData.total) {
                            var userTotal = '.user-total_' + parsedData.hourId;
                            $(userTotal).html('total: ' + parsedData.total);
                        }

                        if (parsedData.less) {
                            var userLess = '.user-less_' + parsedData.hourId;
                            $(userLess).html('less: ' + parsedData.less);
                        }
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
                    function isJson(data) {
                        try {
                            $.parseJSON(data);
                        } catch (e) {
                            return false;
                        }
                        return true;
                    }

                    if (isJson(data)) {
                        var parsedData = $.parseJSON(data);

                        var userTotal = '.user-total_' + parsedData.hourId;
                        $(userTotal).html('total: ' + parsedData.total);
                    }
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        }

        initializeStartAndStop();
        startUpdateInterval();

        {% if lastStartTime is empty %}
            stopUpdateInterval();
        {% endif %}
    });
</script>
