
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

