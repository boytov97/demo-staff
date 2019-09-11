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

                                          {% if user.name === userName %}
                                              {% for hour in user.hours %}
                                                  {% if hour.createdAt == date['date'] %}
                                                      {% set endStop = hour.end ? hour.end : ' - <a href="" name="stop" class="update-hours">stop</a>' %}

                                                      <input type="hidden" value="{{ url(['for': 'hours-update']) }}" id="update-hours-link">
                                                      <center>{{ hour.start ? hour.start ~ ' - ' ~ endStop : '<a href="" name="start" class="update-hours">start</a>' }}</center>
                                                      <center>
                                                          {% if hour.total is not empty %}
                                                              <span class="total-hour">total: {{ hour.total }}</span>
                                                          {% endif %}

                                                          {% if hour.less is not empty %}
                                                              <span class="less-hour">less: {{ hour.less }}</span>
                                                          {% endif %}
                                                      </center>
                                                  {% endif %}
                                              {% endfor %}
                                          {% else %}
                                              {% if currentDate != date['date'] %}
                                                  {% for hour in user.hours %}
                                                      {% if hour.createdAt == date['date'] %}
                                                          <center>{{ hour.start }} - {{ hour.end }}</center>
                                                          <center>
                                                              {% if hour.total is not empty %}
                                                                  <span class="total-hour">total: {{ hour.total }}</span>
                                                              {% endif %}

                                                              {% if hour.less is not empty %}
                                                                  <span class="less-hour">less: {{ hour.less }}</span>
                                                              {% endif %}
                                                          </center>
                                                      {% endif %}
                                                  {% endfor %}
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
        </div>
    </div>
</div>

