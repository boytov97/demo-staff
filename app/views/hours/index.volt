
{{ content() }}

<div class="col-md-12">
    <div class="row">
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
                        {% include 'hours/table' with [
                            'datesMonth': datesMonth,
                            'users': users,
                            'currentDate': currentDate,
                            'userName': userName
                        ] %}
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

        {% if hourStart is defined and hourStart is not empty %}

        function startUpdateInterval() {
            var updateInterval = setInterval(updateWorkingHours, 5000, null, null);
        }

        {% endif %}

        function initializeStartAndStop() {


            $('.update-hours').on('click', function (e) {
                e.preventDefault();

                clearUpdateInterval();

                var element = $(this);
                var updateActions = {};

                if(element.attr('name') === 'start') {
                    updateActions['start'] = 1;
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
                    startUpdateInterval();
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        }

        initializeStartAndStop();
    });
</script>

