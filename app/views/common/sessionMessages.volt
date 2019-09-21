<div class="session_messages_wrapper">
    {% if successMessages is defined %}
        <ul>
            {% for successMessage in successMessages  %}
                <li class="alert alert-success">{{ successMessage }}</li>
            {% endfor %}
        </ul>
    {% endif %}

    {% if errorMessages is defined %}
        <ul>
            {% for successMessage in errorMessages  %}
                <li class="alert alert-danger">{{ successMessage }}</li>
            {% endfor %}
        </ul>
    {% endif %}
</div>