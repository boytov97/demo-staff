{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="create-user__wrapper">
                    <form action="{{ action }}" method="POST">
                        <div class="form-group">
                            <label for="nameInput">Name</label>

                            {{ form.render('name', ['value': (user is defined) ? user.name : '']) }}
                            {{ form.messages('name') }}
                        </div>

                        {% if user is not defined %}
                            <div class="form-group">
                                <label for="nameInput">Login</label>

                                {{ form.render('login', ['value': (user is defined) ? user.login : '']) }}
                                {{ form.messages('login') }}
                            </div>
                        {% endif %}

                        <div class="form-group">
                            <label for="nameInput">Profile</label>

                            {{ form.render('profilesId', ['value': (user is defined) ? user.profilesId : '']) }}
                        </div>

                        {% if user is not defined %}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                {{ form.render('email', ['value': (user is defined) ? user.email : '' ]) }}
                                {{ form.messages('email') }}
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                {{ form.render('password') }}
                                {{ form.messages('password') }}
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                {{ form.render('confirmPassword') }}
                                {{ form.messages('confirmPassword') }}
                            </div>
                        {% endif %}

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>