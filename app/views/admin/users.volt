{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="table__wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Login</th>
                                <th scope="col">Email</th>
                                <th scope="col">Image</th>
                                <th scope="col">Profile</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {% for user in users %}
                                <tr class="{{ (user.active === 'N') ? 'not_active' : '' }}">
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.login }}</td>
                                    <td>{{ user.email }}</td>
                                    <td class="admin_user_image_wrp">
                                        {% if user.image is not empty %}
                                            {{ image(user.image, 'alt': user.name, 'class': 'admin_user_image') }}
                                        {% else %}
                                            {{ image('img/default.jpg', 'alt': user.name, 'class': 'admin_user_image') }}
                                        {% endif %}
                                    </td>
                                    <td>{{ user.profile.name }}</td>
                                    <td>
                                        <div class="action__wrapper">
                                            <a href="{{ url(['for': 'admin-users-edit', 'id': user.id]) }}" class="input-group-addon btn bg-red edit__icon_link" title="edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>

                                        <div class="action__wrapper">
                                            <form action="{{ url(['for': 'admin-users-update-activity', 'id': user.id]) }}" method="POST">
                                                <input type="hidden" name="active" value="{{ (user.active === 'N') ? 'Y' : 'N' }}">
                                                <button type="submit" class="input-group-addon btn bg-red edit__icon_link" title="{{ (user.active === 'N') ? 'activate' : 'deactivate' }}">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>