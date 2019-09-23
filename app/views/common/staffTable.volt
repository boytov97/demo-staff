{% if users is defined %}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" style="width: 200px;">
                    <a data-href="" id="hide-show" class="hide-show">Hide/Show</a>
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
                                    {% if hour.createdAt === date['date'] %}

                                        {% if user.id == authUser['id'] and currentDate === date['date'] or admin is defined and admin and currentDate === date['date']  %}
                                            <span class="user_late_mark_{{ hour.id }} {{ hour.late ? 'auth_user_is_late' : '' }}"></span>
                                            <input type="hidden" id="update-hours-link"
                                                   value="{{ url(['for': 'hours-update-total', 'id': hour.id ]) }}">

                                            {% for startEnd in hour.startEnds %}

                                                {% if loop.last %}
                                                    {% set endStop = startEnd.stop ? startEnd.stop :
                                                        ' - <a data-href="' ~
                                                        url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                        ~ '" name="stop" class="update-hours">stop</a>' %}

                                                    <div class="counter-value__wrapper">
                                                        <span class="start-end_{{ startEnd.id }}">
                                                            {% if startEnd.start is not empty %}
                                                                {% if admin is defined and admin %}
                                                                    <form action="{{ url(['for': 'admin-update-start-end', 'id': startEnd.id]) }}"
                                                                          method="POST" class="start-end__form">

                                                                        <input type="text" name="start" value="{{ startEnd.start }}"
                                                                               class="start-end__input input_without_border" required> -

                                                                        <a data-href="" class="start_end_edit" title="edit">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>

                                                                        <button class="input-group-addon btn start_end_save hidden_start-end_btn"
                                                                                title="save">
                                                                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>

                                                                    <span class="admin_stop_dtn">
                                                                        {{ '<a data-href="' ~
                                                                        url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                                        ~ '" name="stop" class="update-hours">stop</a>' }}
                                                                    </span>
                                                                {% else %}
                                                                    {{ startEnd.start ~ endStop }}
                                                                {% endif %}
                                                            {% else %}
                                                                {{ '<a data-href="' ~
                                                                  url(['for': 'hours-update', 'id': hour.id, 'startEndId': startEnd.id ])
                                                                  ~ '" name="start" class="update-hours">start</a>' }}
                                                            {% endif %}
                                                        </span>
                                                    </div>
                                                {% else %}
                                                    <div class="counter-value__wrapper">
                                                        {% if admin is defined and admin %}
                                                            <form action="{{ url(['for': 'admin-update-start-end', 'id': startEnd.id]) }}"
                                                                  method="POST" class="start-end__form">

                                                                <input type="text" name="start" value="{{ startEnd.start }}"
                                                                       class="start-end__input input_without_border" required> -

                                                                <input type="text" name="stop" value="{{ startEnd.stop }}"
                                                                       class="start-end__input input_without_border" required>

                                                                <input type="hidden" name="month" value="{{ defaultMonth }}">

                                                                <a data-href="" class="start_end_edit" title="edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>

                                                                <button class="input-group-addon btn start_end_save hidden_start-end_btn"
                                                                        title="save">
                                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                                </button>
                                                            </form>
                                                        {% else %}
                                                            <span class="start-end_{{ startEnd.id }}">
                                                                {{ startEnd.start }} - {{ startEnd.stop }}
                                                            </span>
                                                        {% endif %}
                                                    </div>
                                                {% endif %}
                                            {% endfor %}

                                            <div class="counter-value__wrapper">
                                                <span class="total-hour user-total_{{ hour.id }}">
                                                    {% if hour.total is not empty %}
                                                        total: {{ hour.total }}
                                                    {% endif %}
                                                </span>

                                                <span class="less-hour user-less_{{ hour.id }}">
                                                    {% if hour.less is not empty %}
                                                        less: {{ hour.less }}
                                                    {% endif %}
                                                </span>
                                            </div>
                                        {% elseif currentDate !== date['date'] %}
                                            <span class="user_late_mark_{{ hour.id }} {{ hour.late ? 'user_is_late' : '' }}"></span>

                                            {% for startEnd in hour.startEnds %}
                                                {% if admin is defined and admin %}
                                                    <div class="counter-value__wrapper">
                                                        <form action="{{ url(['for': 'admin-update-start-end', 'id': startEnd.id]) }}"
                                                            method="POST" class="start-end__form">

                                                            <input type="text" name="start" value="{{ startEnd.start }}"
                                                                   class="start-end__input input_without_border" required> -

                                                            <input type="text" name="stop" value="{{ startEnd.stop }}"
                                                                   class="start-end__input input_without_border" required>

                                                            <input type="hidden" name="month" value="{{ defaultMonth }}">

                                                            <a data-href="" class="start_end_edit" title="edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>

                                                            <button class="input-group-addon btn start_end_save hidden_start-end_btn"
                                                                    title="save">
                                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                {% else %}
                                                    <div class="counter-value__wrapper">
                                                        {% if startEnd.start === 'forgot' %}
                                                            <span class="forgotten">{{ startEnd.start }}</span> -
                                                        {% else %}
                                                            {{ startEnd.start }} -
                                                        {% endif %}


                                                        {% if startEnd.stop === 'forgot' %}
                                                            <span class="forgotten">{{ startEnd.stop }}</span>
                                                        {% else %}
                                                            {{ startEnd.stop }}
                                                        {% endif %}
                                                    </div>
                                                {% endif %}
                                            {% endfor %}

                                            <p class="counter-value__wrapper">
                                                {% if hour.total is not empty %}
                                                    <span class="total-hour user-total_{{ hour.id }}">total: {{ hour.total }}</span>
                                                {% endif %}

                                                <span class="less-hour user-less_{{ hour.id }}">
                                                    {% if hour.less is not empty %}
                                                        less: {{ hour.less }}
                                                    {% endif %}
                                                </span>
                                            </p>
                                        {% endif %}

                                    {% endif %}
                                {% endfor %}

                                {% if admin is defined and admin %}
                                    {% if date['timestamp'] < currentTimestamp %}
                                        {% if not in_array(date['date'], hoursCreatedAts[user.id]) %}
                                            <div class="counter-value__wrapper">
                                                <form action="{{ url(['for': 'admin-create-counter', 'userId': user.id, 'createdAt': date['date'] ]) }}"
                                                      method="POST" class="start-end__form">

                                                    <input type="text" name="start" value="" placeholder="start time"
                                                           class="start-end__input input_without_border" required> -

                                                    <input type="text" name="stop" value="" placeholder="stop time"
                                                           class="start-end__input input_without_border" required>

                                                    <input type="hidden" name="month" value="{{ defaultMonth }}">

                                                    <a data-href="" class="start_end_edit" title="edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    <button class="input-group-addon btn start_end_save hidden_start-end_btn"
                                                            title="save">
                                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <p class="counter-value__wrapper"></p>
                                        {% endif %}
                                    {% endif %}
                                {% endif %}
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