{% if users is defined %}
    <table class="table table-bordered">
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
                                <input type="checkbox" disabled {{ date['working_day'] ? 'checked' : '' }}>

                                {% for hour in user.hours %}
                                    {% if hour.createdAt == date['date'] %}
                                        {% if admin is defined and admin %}
                                            {% for startEnd in hour.startEnds %}
                                                {% if startEnd.start is not empty or startEnd.stop is not empty %}
                                                    <form action="{{ url(['for': 'admin-update-start-end', 'id': startEnd.id]) }}" class="counter-value__wrapper">
                                                        {% if startEnd.start is not empty %}
                                                            <input type="text" name="start" value="{{ startEnd.start }}"
                                                                   class="start-end__input input_without_border"> -
                                                        {% endif %}

                                                        {% if startEnd.stop is not empty %}
                                                            <input type="text" name="start" value="{{ startEnd.stop }}"
                                                                   class="start-end__input input_without_border">
                                                        {% endif %}

                                                        <a href=""
                                                           class="input-group-addon btn bg-red start_end_edit" title="edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>

                                                        <button type="submit" class="input-group-addon btn bg-red start_end_save  hidden_start-end_btn"
                                                                title="{{ (user.active === 'N') ? 'activate' : 'deactivate' }}">
                                                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                {% endif %}
                                            {% endfor %}
                                        {% else %}
                                            {% if user.id == authUser['id'] and currentDate === date['date'] %}
                                                <span class="{{ hour.late ? 'auth_user_is_late' : '' }}"></span>
                                                <input type="hidden" id="update-hours-link"
                                                       value="{{ url(['for': 'hours-update-total', 'id': hour.id ]) }}">

                                                {% for startEnd in hour.startEnds %}
                                                    {% set endStop = startEnd.stop ? startEnd.stop :
                                                    '<a href="' ~
                                                    url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                    ~ '" name="stop" class="update-hours">stop</a>' %}

                                                    <p class="counter-value__wrapper">
                                                      <span class="start-end_{{ startEnd.id }}">{{ startEnd.start ? startEnd.start ~ ' - ' ~ endStop :
                                                          '<a href="' ~
                                                          url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                          ~ '" name="start" class="update-hours">start</a>' }}
                                                      </span>
                                                    </p>
                                                {% endfor %}

                                                <p class="counter-value__wrapper">
                                                    <span class="total-hour auth-user-total">
                                                        {% if hour.total is not empty %}
                                                            total: {{ hour.total }}
                                                        {% endif %}
                                                    </span>

                                                    <span class="less-hour auth-user-less">
                                                        {% if hour.less is not empty %}
                                                            less: {{ hour.less }}
                                                        {% endif %}
                                                    </span>
                                                </p>
                                            {% elseif currentDate !== date['date'] %}
                                                <span class="{{ hour.late ? 'user_is_late' : '' }}"></span>

                                                {% for startEnd in hour.startEnds %}
                                                    <p class="counter-value__wrapper">{{ startEnd.start }} -
                                                        {% if startEnd.stop === 'forgot' %}
                                                            <span class="forgotten">{{ startEnd.stop }}</span>
                                                        {% else %}
                                                            {{ startEnd.stop }}
                                                        {% endif %}
                                                    </p>
                                                {% endfor %}

                                                <p class="counter-value__wrapper">
                                                    {% if hour.total is not empty %}
                                                        <span class="total-hour">total: {{ hour.total }}</span>
                                                    {% endif %}

                                                    {% if hour.less is not empty %}
                                                        <span class="less-hour">less: {{ hour.less }}</span>
                                                    {% endif %}
                                                </p>
                                            {% endif %}
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