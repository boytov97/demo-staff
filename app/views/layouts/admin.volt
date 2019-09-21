
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03"
            aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ url(['for': 'admin-index']) }}">STAFF ADMIN</a>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ url(['for': 'admin-users-list']) }}">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(['for': 'not-working-days']) }}">Not working days</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(['for': 'permissions-index']) }}">Permissions</a>
            </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
            <a class="nav-link" href="{{ url(['for': 'hours-index']) }}">staff</a>
        </div>
    </div>
</nav>

{{ content() }}
