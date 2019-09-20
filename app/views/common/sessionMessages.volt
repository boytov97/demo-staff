<div class="session_messages_wrapper">
    {% if sessionMessages is not empty %}
        {% if sessionMessages['success'] is defined %}
            <p class="alert alert-success">{{ sessionMessages['success'] }}</p>
        {% else %}
            <ul>
                {% for sessionMessage in sessionMessages  %}
                    {% for message in sessionMessage  %}
                        <li class="alert alert-danger">{{ message }}</li>
                    {% endfor %}
                {% endfor %}
            </ul>
        {% endif %}
    {% endif %}
</div>