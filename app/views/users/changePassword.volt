{{ content() }}

<div class="form__wrapper">
    <form method="POST" action="{{ url(['for': 'users-changePassword']) }}" autocomplete="off">
        <div class="center scaffold">
            <h2>Change Password</h2>

            <div class="form-group">
                <label for="password">Password</label>
                {{ form.render("password") }}
                {{ form.messages('password') }}
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                {{ form.render("confirmPassword") }}
                {{ form.messages('confirmPassword') }}
            </div>

            <div class="form-group">
                {{ submit_button("Change Password", "class": "btn btn-primary") }}
            </div>
        </div>
    </form>
</div>