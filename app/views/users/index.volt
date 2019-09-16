{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="profile__wrapper">
                <form action="{{ url(['for': 'user-profile-update']) }}" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="profile_image_wrapper">
                            {% if user.image is not empty %}
                                {{ image(user.image, 'alt': user.name, 'class': 'img-thumbnail') }}
                                <a href="{{ url(['for': 'user-delete-uploads']) }}" class="delete_file" title="delete image">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            {% endif %}
                        </div>

                        <br>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                {{ form.render("image") }}
                            </div>
                        </div>

                        {{ form.messages('image') }}

                        <label for="nameInput">Name</label>
                        <div class="input-group">
                            {{ form.render("name", ['value': authUser['name'] ? authUser['name'] : '' ]) }}

                            <a href="#" class="input-group-addon btn bg-red edit__name">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div><br>
                        {{ form.messages('name') }}
                    </div>
                    <button type="submit" class="btn btn-primary" id="profile_edit_dtn">Submit</button>
                </form>

                <div class="link_to_change_password">
                    {{ link_to(['for': 'users-changePassword'], 'Change fassword') }}
                </div>
            </div>
        </div>
    </div>
</div>