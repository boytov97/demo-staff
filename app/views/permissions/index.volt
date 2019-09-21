{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="middle_wrapper_block">
                <h3>Manage Permissions</h3>

                <div class="search__wrapper">
                    <form action="{{ url(['for': 'permissions-index']) }}" method="POST">
                        <table class="table">
                            <tr>
                                <td><label for="profileId">Profile</label></td>
                                <td>
                                    <div class="form-group">
                                        <select name="profileId" class="form-control days_select" id="daysSelect">
                                            <option value="">Select a profile</option>

                                            {% for key, value in profiles %}
                                                <option value="{{ value.id }}" {{ (profile is defined and profile.id == value.id ) ? 'selected' : '' }}>
                                                    {{ value.name }}
                                                </option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </td>
                                <td>{{ submit_button('Search', 'class': 'btn btn-primary', 'name' : 'search') }}</td>
                            </tr>
                        </table>

                        {% if request.isPost() and profile %}

                            {% for resource, actions in acl.getResources() %}

                                <h3>{{ resource }}</h3>

                                <table class="table table-bordered table-striped" align="center">
                                    <thead>
                                    <tr>
                                        <th width="5%"></th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {% for action in actions %}
                                        <tr>
                                            <td align="center">
                                                <input type="checkbox" name="permissions[]" value="{{ resource ~ '.' ~ action }}"
                                                        {% if permissions[resource ~ '.' ~ action] is defined %} checked="checked" {% endif %}>
                                            </td>
                                            <td>{{ acl.getActionDescription(action) ~ ' ' ~ resource }}</td>
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>

                            {% endfor %}

                            {{ submit_button('Submit', 'class': 'btn btn-primary', 'name':'submit') }}

                        {% endif %}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>