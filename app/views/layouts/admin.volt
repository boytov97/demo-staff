
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03"
            aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ url(['for': 'admin-index']) }}">STAFF ADMIN</a>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ url(['for': 'admin-users']) }}">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(['for': 'admin-create-user']) }}">Create user</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(['for': 'hours-index']) }}">Hours</a>
            </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
            {% if logged_in is defined and not(logged_in is empty) %}
                <a href="{{ url(['for': 'user-profile']) }}">{{ authUser['name'] }}</a>

                <a href="{{ url(['for': 'session-logout']) }}" class="btn btn-light">Logout</a>
            {% else %}
                <a href="{{ url(['for': 'session-index']) }}" class="btn btn-light">Login</a>
            {% endif %}
        </div>
    </div>
</nav>

{{ content() }}