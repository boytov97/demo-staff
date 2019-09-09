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
                  <tbody>
                    {% for position, date in datesMonth %}
                        <tr class="{{ (currentDate == date['date']) ? 'current_working_line' : 'full_day not_current_working_line' }}">
                          <td scope="row">{{ position }}
                               <span class="day_of_weeks">{{ date['day'] }}</span>
                          </td>

                          {% for user in users %}
                             <td>
                                 {% if user.name === userName %}
                                    {% for hour in user.hours %}
                                        {% if hour.createdAt == date['date'] %}
                                            {{ hour.start ? hour.start : '<a href="">start</a>' }}
                                             - {{ hour.end ? hour.end : '<a href="">stop</a>' }}
                                        {% endif %}
                                    {% endfor %}
                                 {% else %}
                                    {% if currentDate != date['date'] %}
                                        {% for hour in user.hours %}
                                            {% if hour.createdAt == date['date'] %}
                                                {{ hour.start }} - {{ hour.end }}
                                            {% endif %}
                                        {% endfor %}
                                    {% endif %}
                                 {% endif %}
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