<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="{{ url(['for': 'hours-index']) }}">STAFF</a>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="{{ url(['for': 'home']) }}">Home <span class="sr-only">(current)</span></a>
      </li>

      {% if logged_in is defined and logged_in %}
        <li class="nav-item">
          <a class="nav-link" href="{{ url(['for': 'admin-index']) }}">Admin</a>
        </li>
      {% endif %}
    </ul>

    <div class="form-inline my-2 my-lg-0">
        {% if logged_in is defined and logged_in %}
          <a href="{{ url(['for': 'user-profile']) }}">{{ authUser['name'] }}</a>

          <a href="{{ url(['for': 'session-logout']) }}" class="btn btn-light">Logout</a>
        {% else %}
          <a href="{{ url(['for': 'session-index']) }}" class="btn btn-light">Login</a>
        {% endif %}
    </div>
  </div>
</nav>

{{ content() }}
