{{ content() }}

<div class="col-md-12">
    <div class="row">
        <div class="form__wrapper">
            <form action="{{ url(['for': 'session-login']) }}" method="POST">
              {{ form.render('csrf', ['value': security.getToken()]) }}

              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                {{ form.render('email') }}
                {{ form.messages('email') }}
              </div>

              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                {{ form.render('password') }}
                {{ form.messages('password') }}
              </div>

              <div class="form-group form-check">
                {{ form.render('remember') }}
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
              </div>

              {{ form.render('submit') }}
            </form>

            <div class="forgot-password_wrapper">
                <a href="{{ url(['for': 'session-forgot-password']) }}" class="forgot-password_link">Forgot password</a>
            </div>
        </div>
    </div>
</div>