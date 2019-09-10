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
                        {% set endStop = hour.end ? hour.end : ' - <a href="/staff/hours/update" id="stop-time">stop</a>' %}
                        {{ hour.start ? hour.start ~ ' - ' ~ endStop : '<a href="/staff/hours/update" id="start-time">start</a>' }}
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