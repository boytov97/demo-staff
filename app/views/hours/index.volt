<div class="col-md-12">
    <div class="row">
        <div class="year-month_selector">
            <select name="year" id="">
                {% for key, year in years %}
                    <option value="{{ key }}">{{ year }}</option>
                {% endfor %}
            </select>
        </div>

        <div class="table__wrapper">
            <table class="table table-bordered">
             {% if users is defined %}
                  <thead>
                    <tr>
                      <th scope="col" style="width: 200px;">
                        <a href="#" id="hide-show">Hide/Show</a>
                      </th>
                        {% for user in users %}
                             <th scope="col">{{ user.name }}</th>
                        {% endfor %}
                    </tr>
                  </thead>
                  <tbody class="working_table_list">
                      {% for position, date in datesMonth %}
                          <tr class="{{ (currentDate == date['date']) ? 'current_working_line' : 'full_day not_current_working_line' }}">
                              <td scope="row">
                                  <center>
                                      {{ position }} <br>
                                      <span class="day_of_weeks">{{ date['day'] }}</span>
                                  </center>
                              </td>

                              {% for user in users %}
                                  <td>
                                      <div class="hours__wrapper">
                                          <input type="checkbox" disabled checked>

                                          {% for hour in user.hours %}
                                                {% if hour.createdAt == date['date'] %}
                                                    {% if user.id == authUser['id'] and currentDate === date['date'] %}
                                                          <input type="hidden" id="update-hours-link" value="{{ url(['for': 'hours-update-total', 'id': hour.id ]) }}">

                                                          {% for startEnd in hour.startEnds %}
                                                              {% set endStop = startEnd.end ? startEnd.end :
                                                              '<a href="' ~
                                                              url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                              ~ '" name="stop" class="update-hours">stop</a>' %}

                                                              <center>
                                                                  <span class="start-end_{{ startEnd.id }}">{{ startEnd.start ? startEnd.start ~ ' - ' ~ endStop :
                                                                      '<a href="' ~
                                                                      url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                                      ~ '" name="start" class="update-hours">start</a>' }}
                                                                  </span>
                                                              </center>
                                                          {% endfor %}

                                                          <center>
                                                              <span class="total-hour auth-user-total">
                                                                  {% if hour.total is not empty %}
                                                                      total: {{ hour.total }}
                                                                  {% endif %}
                                                              </span>

                                                              {% if hour.less is not empty %}
                                                                  <span class="less-hour">less: {{ hour.less }}</span>
                                                              {% endif %}
                                                          </center>
                                                    {% elseif currentDate !== date['date'] %}
                                                          {% for startEnd in hour.startEnds %}
                                                              <center>{{ startEnd.start }} - {{ startEnd.end }}</center>
                                                          {% endfor %}
                                                          <center>
                                                              {% if hour.total is not empty %}
                                                                  <span class="total-hour">total: {{ hour.total }}</span>
                                                              {% endif %}

                                                              {% if hour.less is not empty %}
                                                                  <br>
                                                                  <span class="less-hour">less: {{ hour.less }}</span>
                                                              {% endif %}
                                                          </center>
                                                    {% endif %}
                                                 {% endif %}
                                          {% endfor %}
                                      </div>
                                  </td>
                              {% endfor %}
                          </tr>
                      {% endfor %}
                  </tbody>
                </table>
             {% else %}
                 <hr>
                 <p>No users</p>
             {% endif %}
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#hide-show').on('click', function () {
            $('.full_day').each(function () {
                $(this).toggleClass('not_current_working_line');
            });
        });

        var updateTotalInterval;

        function startUpdateInterval() {
            updateTotalInterval = setInterval(updateTotal, 5000);
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
                url: element.attr('href'),
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
        startUpdateInterval();

        {% if lastStartTime is empty %}
            stopUpdateInterval();
        {% endif %}
    });
</script>
